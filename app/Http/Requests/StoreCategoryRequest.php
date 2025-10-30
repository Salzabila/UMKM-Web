<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class StoreCategoryRequest extends FormRequest
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
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Category::whereRaw('LOWER(nama) = ?', [strtolower($value)])->exists();
                    if ($exists) {
                        $fail('Nama mitra binaan sudah ada (tidak boleh sama meskipun berbeda huruf besar/kecil).');
                    }
                },
            ],
            'nomor_penanggung_jawab' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
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
            'nama.required' => 'Nama mitra binaan wajib diisi.',
            'nama.string' => 'Nama mitra binaan harus berupa teks.',
            'nama.max' => 'Nama mitra binaan tidak boleh lebih dari 255 karakter.',
            'nomor_penanggung_jawab.string' => 'Nomor penanggung jawab harus berupa teks.',
            'nomor_penanggung_jawab.max' => 'Nomor penanggung jawab tidak boleh lebih dari 20 karakter.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
        ];
    }
}
