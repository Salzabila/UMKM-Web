<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Good;

class UpdateGoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $goodId = $this->route('good')->id ?? $this->input('id');

        return [
            'category_id' => 'required|exists:categories,id',
            'tgl_masuk' => 'required|date',
            'nama' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($goodId) {
                    $exists = Good::whereRaw('LOWER(nama) = ?', [strtolower($value)])
                        ->where('id', '!=', $goodId)
                        ->exists();
                    if ($exists) {
                        $fail('Nama barang sudah ada (tidak boleh sama meskipun berbeda huruf besar/kecil).');
                    }
                },
            ],
            'type' => 'required|in:makanan,non_makanan,lainnya,handycraft,fashion',
            'expired_date' => 'nullable|date|after:today',
            'stok' => 'required|integer|min:0',
            'harga_asli' => 'required|numeric|min:0',
            'markup_percentage' => 'nullable|numeric|min:0|max:100',
            'use_existing_barcode' => 'boolean',
            'existing_barcode' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.required' => 'Kategori barang wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'tgl_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tgl_masuk.date' => 'Format tanggal masuk tidak valid.',
            'nama.required' => 'Nama barang wajib diisi.',
            'nama.string' => 'Nama barang harus berupa teks.',
            'nama.max' => 'Nama barang tidak boleh lebih dari 255 karakter.',
            'type.required' => 'Jenis barang wajib dipilih.',
            'type.in' => 'Jenis barang tidak valid.',
            'expired_date.date' => 'Format tanggal expired tidak valid.',
            'expired_date.after' => 'Tanggal expired harus setelah hari ini.',
            'stok.required' => 'Stok barang wajib diisi.',
            'stok.integer' => 'Stok barang harus berupa angka.',
            'stok.min' => 'Stok barang tidak boleh kurang dari 0.',
            'harga_asli.required' => 'Harga asli barang wajib diisi.',
            'harga_asli.numeric' => 'Harga asli barang harus berupa angka.',
            'harga_asli.min' => 'Harga asli barang tidak boleh kurang dari 0.',
            'markup_percentage.numeric' => 'Persentase markup harus berupa angka.', 
            'markup_percentage.min' => 'Persentase markup tidak boleh kurang dari 0.', 
            'markup_percentage.max' => 'Persentase markup tidak boleh lebih dari 100.',
            'existing_barcode.string' => 'Barcode harus berupa teks.',
            'existing_barcode.max' => 'Barcode tidak boleh lebih dari 255 karakter.',
        ];
    }
}
