<?php

namespace App\Repositories;

use App\Http\Requests\ProductRequest;
use App\Interfaces\ProductInterfaces;
use App\Models\ProductModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


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
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = 'IMG-product-' . Str::random(15) . '.' . $extension;
                Storage::makeDirectory('uploads/img-product');
                $file->move(public_path('uploads/img-product'), $filename);
                $data->image = $filename;
            }
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
            // Cari data berdasarkan ID
            $data = $this->ProductModel::where('id', $id)->first();
            if (!$data) {
                return $this->dataNotFound();
            }

            // Simpan nama dan harga produk
            $data->name = $request->input('name');
            $data->price = $request->input('price');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = 'IMG-product-' . Str::random(15) . '.' . $extension;

                // Buat folder jika belum ada
                $uploadPath = public_path('uploads/img-product');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Hapus file lama jika ada dan bukan direktori
                $oldFilePath = public_path('uploads/img-product/' . $data->image);
                if (!empty($data->image) && file_exists($oldFilePath) && is_file($oldFilePath)) {
                    unlink($oldFilePath);
                }

                // Simpan file baru
                $file->move($uploadPath, $filename);

                // Simpan nama file baru di database
                $data->image = $filename;
            }

            // Simpan perubahan
            $data->update();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }


    public function deleteDataById($id)
    {
        try {
            // Temukan data produk berdasarkan ID
            $data = $this->ProductModel::findOrFail($id);

            // Periksa apakah file ada dan hapus dari storage
            if (!empty($data->image)) {
                $filePath = public_path('uploads/img-product/' . $data->image);
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
            }

            // Hapus data produk dari database
            $data->delete();

            return $this->success(['message' => 'Data produk berhasil dihapus.']);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
