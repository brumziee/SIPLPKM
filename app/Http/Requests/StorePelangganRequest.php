<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID dari route parameter
        $pelangganId = $this->route('id');

        $rules = [
            'Nama_Pelanggan' => 'required|string|max:255',
            'NoTelp_Pelanggan' => [
                'required',
                'string',
                'max:20',
            ],
            'Jumlah_Poin' => 'nullable|integer|min:0',
        ];

        // Jika sedang update (ada ID), ignore nomor telepon yang sama dengan ID tersebut
        if ($pelangganId) {
            $rules['NoTelp_Pelanggan'][] = Rule::unique('pelanggan', 'NoTelp_Pelanggan')
                ->ignore($pelangganId, 'ID_Pelanggan');
        } else {
            // Jika create, nomor telepon harus unique
            $rules['NoTelp_Pelanggan'][] = 'unique:pelanggan,NoTelp_Pelanggan';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'Nama_Pelanggan.required' => 'Nama pelanggan wajib diisi',
            'Nama_Pelanggan.string' => 'Nama pelanggan harus berupa teks',
            'Nama_Pelanggan.max' => 'Nama pelanggan maksimal 255 karakter',
            'NoTelp_Pelanggan.required' => 'Nomor telepon wajib diisi',
            'NoTelp_Pelanggan.string' => 'Nomor telepon harus berupa teks',
            'NoTelp_Pelanggan.max' => 'Nomor telepon maksimal 20 karakter',
            'NoTelp_Pelanggan.unique' => 'Nomor telepon sudah terdaftar untuk pelanggan lain',
            'Poin_Loyalitas.integer' => 'Poin harus berupa angka',
            'Poin_Loyalitas.min' => 'Poin minimal 0',
            'Jumlah_Poin.integer' => 'Poin harus berupa angka',
            'Jumlah_Poin.min' => 'Poin minimal 0',
        ];
    }
}