<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'member_id',
        'subscriber_id',    // string or integer depending on your Subscribers table
        'tran_id',
        'package_id',
        'amount',
        'currency',
        'status',
        'gateway',          // e.g. sslcommerz
        'transaction_id',   // bank_tran_id / val_id
        'gateway_payload',  // JSON payload from gateway
        'method',           // e.g. bkash, cash
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_payload' => 'array', // requires payments.gateway_payload to be json column
    ];

    /**
     * Relation to Member (members created by payments).
     * Assumes Members model lives in Modules\Members\Models\Member and primary key is id.
     */
    public function member()
    {
        return $this->belongsTo(\Modules\Members\Models\Member::class, 'member_id', 'id');
    }

    /**
     * Relation to Subscriber.
     * Assumes Subscribers model uses a subscriber_id string key (as used in controller).
     * If your Subscribers table primary key is 'id' and you store that in payments.subscriber_id,
     * change the third argument ('subscriber_id') to 'id'.
     */
    public function subscriber()
    {
        return $this->belongsTo(\Modules\Subscribers\Models\Subscriber::class, 'subscriber_id', 'subscriber_id');
    }
}
