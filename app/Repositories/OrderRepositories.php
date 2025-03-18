<?php

namespace App\Repositories;

use App\Http\Requests\OrderRequest;
use App\Interfaces\OrderInterfaces;
use App\Models\BillingItemsModel;
use App\Models\BillingsModel;
use App\Models\CustomersModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\DB;

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
    public function getAllData() {}
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

            // 3️⃣ Ambil data dari localStorage (simulasi dengan request)
            $cartItems = json_decode($request->cart_items, true);

            if (empty($cartItems)) {
                return response()->json(['message' => 'Keranjang belanja kosong!'], 400);
            }

            // 4️⃣ Hitung total pembayaran
            $totalPayment = array_sum(array_column($cartItems, 'total_price'));

            // 5️⃣ Simpan data billing
            $billing = new $this->billings;
            $billing->id_customer = $customer->id;
            $billing->total_payment = $totalPayment;
            $billing->payment_date = now();
            $billing->code_transaction = $transactionCode;
            $billing->save();

            // 6️⃣ Simpan data item yang dibeli
            foreach ($cartItems as $item) {
                $billingItem = new $this->billingsItems;
                $billingItem->id_billing = $billing->id;
                $billingItem->id_product = $item['id_product'];
                $billingItem->name_product = $item['name_product'];
                $billingItem->price_product = $item['price_product'];
                $billingItem->qty = $item['qty'];
                $billingItem->total_price = $item['total_price'];
                $billingItem->save();
            }

            DB::commit();

            return $this->success([
                "customer" => $customer,
                "billing" => $billing,
                "billingItems" => $billingItem,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan!', 'error' => $th->getMessage()], 400);
        }
    }
}
