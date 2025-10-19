<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Orders\Database\Factories\OrderItemFactory;

class OrderItem extends Model
{
    use Hasfactory;
    protected $fillable = [
        'order_id','package_id','qty','unit_price','line_total','access_starts_at','access_expires_at','meta'
    ];

    protected $casts = [
        'access_starts_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'meta' => 'array',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function package() { return $this->belongsTo(Package::class); }
}