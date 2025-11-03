<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRewardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cek user login dan role
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'kasir']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'Nama_Reward' => ['required', 'string', 'max:255'],
            'Poin_Dibutuhkan' => ['required', 'integer', 'min:1'],
        ];

        if ($this->hasFile('image')) {
            $rules['image'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
        }

        return $rules;
    }

    /**
     * Custom messages untuk validator.
     */
    public function messages(): array
    {
        return [
            'Nama_Reward.required' => 'Nama reward harus diisi',
            'Nama_Reward.max' => 'Nama reward maksimal 255 karakter',
            'Poin_Dibutuhkan.required' => 'Poin yang dibutuhkan harus diisi',
            'Poin_Dibutuhkan.integer' => 'Poin harus berupa angka',
            'Poin_Dibutuhkan.min' => 'Poin minimal 1',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
