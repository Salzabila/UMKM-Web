<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBiayaOperasionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Adjust authorization logic as needed. For now, allow authenticated users.
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'uraian' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'qty' => 'required|integer|min:1',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'uraian.required' => 'Uraian atau keterangan wajib diisi.',
            'uraian.string' => 'Uraian harus berupa teks.',
            'uraian.max' => 'Uraian tidak boleh lebih dari 255 karakter.',
            'nominal.required' => 'Nominal wajib diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal tidak boleh kurang dari 0.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal harus dalam format tanggal yang valid.',
            'qty.required' => 'Kuantitas (Qty) wajib diisi.',
            'qty.integer' => 'Kuantitas (Qty) harus berupa bilangan bulat.',
            'qty.min' => 'Kuantitas (Qty) tidak boleh kurang dari 1.',
        ];
    }
}
