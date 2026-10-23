<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'customer_id',
        'starts_at',
        'ends_at',
        'total_price',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}