<?php

namespace Modules\Subscribers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Subscribers\Database\Factories\SubscriberFactory;

class Subscriber extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guard = 'subscriber';

    protected $fillable = [
        'subscriber_id','username','full_name','name_bn','email','phone','password',
        'dob','gender','profession','other_expertise',
        'country','division','district','address',
        'plan','status','started_at','expires_at',
        'last_payment_amount','last_payment_tran_id','last_payment_at','last_payment_gateway',
        'balance',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'dob' => 'date',
        'started_at' => 'date',
        'expires_at' => 'date',
        'last_payment_at' => 'datetime',
    ];
}