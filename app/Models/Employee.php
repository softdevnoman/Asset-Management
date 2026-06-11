<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'employee_id',
        'position',
        'department',
        'phone',
        'profile_photo',
        'join_date',
        'status',
    ];

    protected function casts()
    {
        return [
            'join_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function assignments(): HasMany
    // {
    //     return $this->hasMany(AssetAssignment::class);
    // }
}
