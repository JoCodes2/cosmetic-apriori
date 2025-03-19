<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Repositories\OrderRepositories;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepo;
    public function __construct(OrderRepositories $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }
    public function getAllData()
    {
        return $this->orderRepo->getAllData();
    }
    public function createData(OrderRequest $request)
    {
        return $this->orderRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->orderRepo->getDataById($id);
    }
}
