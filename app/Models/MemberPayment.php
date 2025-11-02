<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberPayment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id', 'tran_id', 'plan', 'amount', 'currency', 'status',
        'bank_tran_id', 'val_id', 'card_type',
        'full_name', 'name_bn', 'username', 'email', 'phone', 'dob',
        'gender', 'blood_group', 'id_number', 'education_qualification',
        'profession', 'other_expertise', 'country', 'division', 'district',
        'address', 'membership_type', 'profile_pic', 'gateway_payload',

        // --- FIX: ADDED MISSING FIELDS ---
        'password_hash', // Required by RegistrationController
        'package_id',    // Required by SslcommerzController
        'package_name',  // Required by SslcommerzController
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gateway_payload' => 'array',
        'amount' => 'decimal:2',
        'dob' => 'date',
    ];

    /**
     * Get the member that owns the payment.
     */
    public function member()
    {
        return $this->belongsTo(\Modules\Members\Models\Member::class);
    }
}


