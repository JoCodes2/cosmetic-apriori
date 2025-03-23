<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\BillingItemsModel;
use App\Models\ProductModel;
use App\Repositories\OrderRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Association\Apriori;

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

    public function getTopProducts()
    {
        return $this->orderRepo->getTopProducts();
    }
    public function getRecommendedProducts(Request $request)
    {
        return $this->orderRepo->getRecommendedProducts($request);
    }
}
