<?php

namespace Modules\Members\Models;

use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName; // ✅ ADD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Members\Database\Factories\MemberFactory;
use Spatie\Permission\Traits\HasRoles;

class Member extends Authenticatable implements FilamentUser, HasName // ✅ ADD HasName
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $table = 'members';
    protected $guard_name = 'member';

    protected $fillable = [
        'profile_pic','member_id','username','name_bn','full_name','email','phone','password',
        'father_name','mother_name','dob','id_number','gender','blood_group',
        'education_qualification','profession','other_expertise','country','division',
        'district','address','membership_type','registration_date','balance',
        'remember_token','email_verified_at',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'dob' => 'date',
        'registration_date' => 'date',
        'balance' => 'decimal:2',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public const GENDERS = ['Male','Female','Other'];
    public const BLOOD_GROUPS = ['A+','A-','B+','B-','O+','O-','AB+','AB-'];
    public const MEMBERSHIP_TYPES = ['Student','General','Premium','Lifetime'];

    protected static function newFactory(): MemberFactory
    {
        return MemberFactory::new();
    }

    // FilamentUser: কোন প্যানেলে ঢুকতে পারবে
    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'member';
    }

    // HasName: Filament UI তে দেখানোর জন্য নাম
    public function getFilamentName(): string
    {
        // সবশেষে ফোর্স করে খালি স্ট্রিং কাস্ট করা হচ্ছে যাতে কখনও null না যায়
        return (string) ($this->full_name ?? $this->username ?? $this->email ?? '');
    }
}
