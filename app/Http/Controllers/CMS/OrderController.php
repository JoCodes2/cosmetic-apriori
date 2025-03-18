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
        $data = $this->orderRepo->getAllData();
    }
    public function createData(OrderRequest $request)
    {
        $data = $this->orderRepo->createData($request);
    }
}
