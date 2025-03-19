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
            $billing->status_transaction = "not_done";
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

    public function getDataById($id){
        $data = $this->billings::with(['customer', 'billingItems'])->where('id', $id)->first();
        if (!$data) {
            return $this->dataNotFound();
        } else {
            return $this->success($data);
        }
    }
}
