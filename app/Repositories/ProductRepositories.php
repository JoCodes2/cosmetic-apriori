<?php

namespace App\Repositories;

use App\Http\Requests\ProductRequest;
use App\Interfaces\ProductInterfaces;
use App\Models\ProductModel;
use App\Traits\HttpResponseTrait;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\Hash;



class ProductRepositories implements ProductInterfaces
{
    use HttpResponseTraits;
    protected $ProductModel;
    public function __construct(ProductModel $ProductModel)
    {
        $this->ProductModel = $ProductModel;
    }

    public function getAllData()
    {
        $data = $this->ProductModel::all();
        if (!$data) {
            return $this->dataNotFound();
        } else {
            return $this->success($data);
        }
    }
    public function createData(ProductRequest $request)
    {
        try {
            // Create the product
            $data = new $this->ProductModel;
            $data->name = $request->input('name');
            $data->price = $request->input('price');

            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $data = $this->ProductModel::where('id', $id)->first();
        if ($data) {
            return $this->success($data);
        } else {
            return $this->dataNotFound();
        }
    }

    public function updateDataById(ProductRequest $request, $id)
    {
        try {
            // Temukan data pengguna berdasarkan ID
            $data = $this->ProductModel::findOrFail($id);

            // Perbarui data pengguna
            $data->name = $request->input('name');
            $data->price = $request->input('price');


            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function deleteDataById($id)
    {
        try {
            // Temukan data pengguna berdasarkan ID
            $data = $this->ProductModel::findOrFail($id);

            // Hapus data pengguna
            $data->delete();

            return $this->success("Data produk berhasil dihapus.");
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
