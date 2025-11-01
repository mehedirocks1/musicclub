<?php

namespace Modules\Members\Models;

use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Members\Database\Factories\MemberFactory;
use Spatie\Permission\Traits\HasRoles;

class Member extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $table = 'members';
    protected $guard_name = 'member';

    protected $fillable = [
        'profile_pic','member_id','username','name_bn','full_name','email','phone','password',
        'father_name','mother_name','dob','id_number','gender','blood_group',
        'education_qualification','profession','other_expertise','country','division',
        'district','address','membership_type','membership_plan','membership_status',
        'membership_started_at','membership_expires_at','registration_date','balance',
        'last_payment_amount','last_payment_tran_id','last_payment_at','last_payment_gateway',
        'status','remember_token','email_verified_at',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'dob' => 'date',
        'registration_date' => 'date',
        'membership_started_at' => 'date',
        'membership_expires_at' => 'date',
        'last_payment_at' => 'datetime',
        'balance' => 'decimal:2',
        'last_payment_amount' => 'decimal:2',
        'email_verified_at' => 'datetime',
    ];

    public const GENDERS = ['Male','Female','Other'];
    public const BLOOD_GROUPS = ['A+','A-','B+','B-','O+','O-','AB+','AB-'];
    public const MEMBERSHIP_TYPES = ['Student','General','Premium','Lifetime'];
    public const MEMBERSHIP_PLANS = ['monthly','yearly'];
    public const MEMBERSHIP_STATUS = ['pending','active','expired','inactive'];
    public const STATUS = ['active','inactive'];

    protected static function newFactory(): MemberFactory
    {
        return MemberFactory::new();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'member';
    }

    public function getFilamentName(): string
    {
        return (string) ($this->full_name ?? $this->username ?? $this->email ?? '');
    }

    public function setPasswordAttribute($value)
    {
        if (!empty($value) && !\Illuminate\Support\Str::startsWith($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }
}
