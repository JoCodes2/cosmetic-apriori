<?php

namespace App\Interfaces;

use App\Http\Requests\OrderRequest;

interface OrderInterfaces
{
    public function getAllData();
    public function createData(OrderRequest $request);
    public function getDataById($id);
    public function getTopProducts();

    public function updateStatus($id, $status);
}
