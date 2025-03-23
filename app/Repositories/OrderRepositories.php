<?php

namespace App\Repositories;

use App\Http\Requests\OrderRequest;
use App\Interfaces\OrderInterfaces;
use App\Models\BillingItemsModel;
use App\Models\BillingsModel;
use App\Models\CustomersModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Association\Apriori;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;



class OrderRepositories implements OrderInterfaces
{
    use HttpResponseTraits;
    protected $billingsItems;
    protected $billings;
    protected $customers;
    public function __construct(CustomersModel $customers, BillingItemsModel $billingItems, BillingsModel $billings)
    {
        $this->billingsItems = $billingItems;
        $this->billings = $billings;
        $this->customers = $customers;
    }
    public function getAllData()
    {
        $data = $this->billings::with(['customer', 'billingItems'])->get();
        if (!$data) {
            return $this->dataNotFound();
        } else {
            return $this->success($data);
        }
    }
    public function createData(OrderRequest $request)
    {
        try {
            DB::beginTransaction();

            // 1️⃣ Simpan data customer
            $customer = new $this->customers;
            $customer->fill($request->only(['name', 'address', 'phone']));
            $customer->save();

            // 2️⃣ Generate kode transaksi unik
            $lastBilling = $this->billings->latest()->first();
            $lastNumber = $lastBilling ? (int) substr($lastBilling->code_transaction, -5) : 0;
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            $transactionCode = 'INV-' . now()->format('Ymd-His') . '-' . $newNumber;

            // 3️⃣ Ambil data dari localStorage untuk menghitung total payment
            $total_payment = 0;
            foreach ($request->input('orders') as $order) {
                $total_payment += $order['total_price'];
            }

            // 5️⃣ Simpan data billing
            $billing = new $this->billings;
            $billing->id_customer = $customer->id;
            $billing->total_payment = $total_payment;
            $billing->status_transaction = "unpaid";
            $billing->payment_date = now();
            $billing->code_transaction = $transactionCode;
            $billing->save();

            // 6️⃣ Simpan data item yang dibeli
            $billingItem = new $this->billingsItems;
            $billingItemCollection = [];
            foreach ($request->input('orders') as $order) {
                $order['id_billing'] = $billing->id;
                $billingItemCollection[] = $billingItem->create($order);
            }

            $data = [
                "id" => $billing->id,
                "customer" => $customer,
                "billing" => $billing,
                "billingItems" => $billingItemCollection,
            ];
            DB::commit();

            return $this->success($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $data = $this->billings::with(['customer', 'billingItems'])->where('id', $id)->first();
        if (!$data) {
            return $this->dataNotFound();
        } else {
            return $this->success($data);
        }
    }
    public function getTopProducts()
    {
        $transactions = DB::table('billings_items')
            ->select('id_billing', DB::raw('GROUP_CONCAT(id_product ORDER BY id_product ASC) as products'))
            ->groupBy('id_billing')
            ->get()
            ->pluck('products')
            ->map(fn($item) => explode(',', $item))
            ->toArray();

        $productStats = DB::table('billings_items')
            ->select('id_product', DB::raw('COUNT(DISTINCT id_billing) as transaction_count'), DB::raw('SUM(qty) as total_sold'))
            ->groupBy('id_product')
            ->orderByDesc('transaction_count')
            ->get();

        $products = DB::table('tb_product')
            ->whereIn('id', $productStats->pluck('id_product'))
            ->get()
            ->keyBy('id');
        $topProducts = $productStats->map(function ($product) use ($products) {
            $productDetail = $products[$product->id_product] ?? null;
            return [
                'id' => $product->id_product,
                'name' => $productDetail->name ?? 'Unknown',
                'price' => $productDetail->price ?? 0,
                'image' => $productDetail->image ?? null,
                'total_sold' => $product->total_sold,
                'transaction_count' => $product->transaction_count,
            ];
        });

        $support = 0.2;
        $confidence = 0.5;
        $apriori = new Apriori($support, $confidence);
        $apriori->train($transactions, []);
        $rules = $apriori->getRules();

        return response()->json([
            'code' => 200,
            'message' => 'Top 4 Products based on sales & transactions',
            'top_products' => $topProducts,
            'association_rules' => $rules
        ]);
    }
    public function getRecommendedProducts(Request $request)
    {
        $productIds = $request->input('product_ids'); // Produk yang ada di keranjang
        $topProductsResponse = $this->getTopProducts();
        $topProducts = collect($topProductsResponse->getData()->top_products);

        // Buat pasangan produk
        $productPairs = [];
        for ($i = 0; $i < count($topProducts); $i += 2) {
            if (isset($topProducts[$i + 1])) {
                $productPairs[$topProducts[$i]->id] = $topProducts[$i + 1];
                $productPairs[$topProducts[$i + 1]->id] = $topProducts[$i];
            }
        }

        $recommendedProducts = [];
        foreach ($productIds as $productId) {
            if (isset($productPairs[$productId])) {
                $pairedProduct = $productPairs[$productId];

                // Jika produk pasangan belum ada di keranjang & belum ada di rekomendasi
                if (!in_array($pairedProduct->id, $productIds) && !collect($recommendedProducts)->contains('id', $pairedProduct->id)) {
                    $recommendedProducts[] = $pairedProduct;
                }
            }
        }

        // **Pastikan rekomendasi tidak hilang walaupun produk sudah dipilih**
        return response()->json([
            'code' => 200,
            'message' => count($recommendedProducts) > 0 ? "Recommended products found." : "No recommendation available.",
            'recommended_products' => array_values($recommendedProducts) // Reset index array
        ]);
    }

    public function updateStatus($id, $status)
    {
        $order = $this->billings->find($id); // Menggunakan BillingsModel

        if (!$order) {
            return ['success' => false, 'message' => 'Order tidak ditemukan'];
        }

        $order->status_transaction = $status;
        $order->save();

        return ['success' => true, 'message' => 'Status berhasil diperbarui', 'order' => $order];
    }
}
