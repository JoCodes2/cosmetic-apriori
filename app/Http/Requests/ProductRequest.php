<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|max:50',
            'price' => 'required|numeric',
            'image' => $this->is('v1/product/update/*') ? 'image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',

        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'nama Produk wajib diisi',
            'name.max' => 'nama kedai tidak boleh lebih dari 50 karakter',

            'price.required' => 'harga wajib diisi',
            'price.numeric' => 'harga harus berupa bilangan',

            'image.required' => 'Gambar produk wajib diunggah.',
            'image.image' => 'Gambar produk harus berupa file gambar.',
            'image.mimes' => 'Gambar produk harus berformat jpeg, png, atau jpg.',
            'image.max' => 'Ukuran gambar produk tidak boleh lebih dari 2MB.',

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'message' => 'Check your validation',
            'errors' => $validator->errors()
        ]));
    }
}
