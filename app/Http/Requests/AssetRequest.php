<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
     * Handles both store (POST) and update (PUT/PATCH) in one place.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // On update, ignore the current asset's own unique values
        $asset   = $this->route('asset');
        $assetId = $asset instanceof \App\Models\Asset ? $asset->id : $asset;

        $uniqueSuffix = $this->isMethod('POST') ? '' : ',' . $assetId;

        return [
            'asset_code'       => 'required|string|max:50|unique:assets,asset_code' . $uniqueSuffix,
            'name'             => 'required|string|max:255',
            'serial_number'    => 'required|string|max:100|unique:assets,serial_number' . $uniqueSuffix,
            'purchase_price'   => 'nullable|numeric|min:0',
            'purchase_date'    => 'nullable|date',
            'condition'        => 'nullable|string|in:Excellent,Good,Fair,Poor,Under Repair',
            'warranty_expiry'  => 'nullable|date',
            'maintenance_date' => 'nullable|date',
            'notes'            => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'asset_code.required'    => 'Asset code is required.',
            'asset_code.unique'      => 'This asset code is already in use.',
            'name.required'          => 'Asset name is required.',
            'serial_number.required' => 'Serial number is required.',
            'serial_number.unique'   => 'This serial number is already registered.',
            'purchase_price.numeric' => 'Purchase price must be a valid number.',
            'purchase_price.min'     => 'Purchase price cannot be negative.',
            'purchase_date.date'     => 'Purchase date must be a valid date.',
            'warranty_expiry.date'   => 'Warranty expiry must be a valid date.',
            'maintenance_date.date'  => 'Maintenance date must be a valid date.',
            'condition.in'           => 'Condition must be one of: Excellent, Good, Fair, Poor, Under Repair.',
        ];
    }
}
