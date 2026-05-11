<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $asset = $this->route('asset');
        $assetId = $asset instanceof \App\Models\Asset? $asset->id : $asset;

        return [
            'name' => 'required|string|max:255',
            'serial_no' => ['required', 'string', 'max:100', 'unique:assets,serial_number,'. $assetId],
            'category' => 'required|string|max:100',
            'status' => 'required|string|max:50',
        ];
    }
}
