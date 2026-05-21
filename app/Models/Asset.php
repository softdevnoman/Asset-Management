<?php

namespace App\Models;

use App\Enums\AssetCondition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'name',
        'serial_number',
        'purchased_date',
        'purchased_price',
        'condition',
        'warranty_expiry',
        'maintenance_date',
        'notes',
        'category_id',
        'current_value',
        'location_id',
        'assign_to',
        'supplier_id',
    ];

    protected $casts = [
        'warranty_expiry' => 'date',
        'maintenance_date' => 'date',
        'purchased_price' => 'decimal:2',
    ];

    protected $appends = [
        'purchase_price',
        'purchase_date',
    ];

    public function getPurchasePriceAttribute()
    {
        return $this->purchased_price;
    }

    public function getPurchaseDateAttribute()
    {
        // purchased_date is varchar in DB, return as-is or format if it looks like a date
        $val = $this->attributes['purchased_date'] ?? null;
        if ($val) {
            try {
                return \Carbon\Carbon::parse($val)->format('Y-m-d');
            } catch (\Exception $e) {
                return $val;
            }
        }
        return null;
    }

    /**
     * Override the condition accessor to handle values not in the enum gracefully.
     */
    public function getConditionAttribute($value)
    {
        if ($value === null) {
            return null;
        }

        // Try to match the enum; if it fails, return the raw string
        $enum = AssetCondition::tryFrom($value);
        return $enum ? $enum->value : $value;
    }
}
