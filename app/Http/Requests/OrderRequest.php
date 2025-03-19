<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|digits_between:10,15',
            'address' => 'required|string|max:500',
        ];
    }

    /**
     * Custom messages for validation rules.
     */
    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.digits_between' => 'Nomor HP harus memiliki minimal 10 digit dan maksimal 15 digit.',
            'address.required' => 'Alamat tidak boleh kosong.',
            'address.string' => 'Alamat harus berupa teks.',
            'address.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'message' => 'Check your validation',
            'errors' => $validator->errors()
        ], 422));
    }
}
