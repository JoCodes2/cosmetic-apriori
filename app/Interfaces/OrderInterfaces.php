<?php

namespace App\Interfaces;

use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;

interface OrderInterfaces
{
    public function getAllData();
    public function createData(OrderRequest $request);
    public function getDataById($id);
    public function getTopProducts();
    public function getRecommendedProducts(Request $request);
    public function updateStatus($id, $status);
    public function getTodayOrders();
}
