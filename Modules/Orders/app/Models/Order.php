<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'status',
        'gateway',
        'tran_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'currency',
        'paid_at',
        'meta',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'meta'    => 'array',
    ];

    // Relations
    public function buyer()
    {
        return $this->morphTo();
    }

    public function items()
    {
        // Fully qualified class to avoid namespace issues
        return $this->hasMany(\Modules\Orders\Models\OrderItem::class);
    }
}
