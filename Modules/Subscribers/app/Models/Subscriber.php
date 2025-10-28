<?php

namespace Modules\Subscribers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscriber extends Model
{
    use SoftDeletes, HasFactory;

    protected $guard = 'subscriber';

    protected $fillable = [
        // Identifiers
        'subscriber_id',

        // Basic profile
        'username', 'full_name', 'name_bn', 'email', 'phone', 'password',
        'profile_pic',

        // Personal details
        'dob', 'gender', 'blood_group', 'id_number', 'education',
        'profession', 'other_expertise',

        // Raw form snapshot
        'registration_snapshot',

        // Address
        'country', 'division', 'district', 'address',

        // Package / one-time fee
        'package_title', 'package_slug', 'fee_type', 'fee_amount',

        // Subscription (legacy/recurring)
        'plan', 'status', 'started_at', 'expires_at',

        // Payment summary
        'last_payment_amount', 'last_payment_tran_id', 'last_payment_at',
        'last_payment_gateway', 'balance',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'dob'               => 'date',
        'started_at'        => 'date',
        'expires_at'        => 'date',
        'last_payment_at'   => 'datetime',
        'registration_snapshot' => 'array',
        'fee_amount'        => 'decimal:2',
        'last_payment_amount' => 'decimal:2',
        'balance'           => 'decimal:2',
    ];
}
