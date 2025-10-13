<?php

namespace Modules\Members\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Members\Database\Factories\MemberFactory;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'members';

    protected $fillable = [
        'profile_pic',
        'member_id',
        'username',
        'name_bn',
        'full_name',
        'email',
        'phone',
        'father_name',
        'mother_name',
        'dob',
        'id_number',
        'gender',
        'blood_group',
        'education_qualification',
        'profession',
        'other_expertise',
        'country',
        'division',
        'district',
        'address',
        'membership_type',
        'registration_date',
        'balance',
    ];

    protected $casts = [
        'dob' => 'date',
        'registration_date' => 'date',
        'balance' => 'decimal:2',
    ];

    // Select options
    public const GENDERS = ['Male', 'Female', 'Other'];
    public const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    public const MEMBERSHIP_TYPES = ['Student', 'General', 'Premium', 'Lifetime'];

    /**
     * Factory binding for Laravel Modules
     */
    protected static function newFactory(): MemberFactory
    {
        return MemberFactory::new();
    }
}
