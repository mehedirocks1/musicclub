<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberPayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id', 'tran_id', 'plan', 'amount', 'currency', 'status',
        'bank_tran_id', 'val_id', 'card_type',
        'full_name', 'name_bn', 'username', 'email', 'phone', 'dob',
        'gender', 'blood_group', 'id_number', 'education_qualification',
        'profession', 'other_expertise', 'country', 'division', 'district',
        'address', 'membership_type', 'profile_pic', 'gateway_payload',
    ];

    protected $casts = [
        'gateway_payload' => 'array',
        'amount' => 'decimal:2',
        'dob' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(\Modules\Members\Models\Member::class);
    }
}
