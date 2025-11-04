<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Members\Models\Member;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'member_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function initials(): string
    {
        $name = (string)$this->name;
        if ($name === '') return '';
        return Str::of($name)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('')
            ->upper()
            ->toString();
    }

    /**
     * Assign a role if the user doesn't have one yet.
     */
    public function assignDefaultRole(string $roleName): void
    {
        if (!$this->roles()->exists()) {
            $role = \Spatie\Permission\Models\Role::where('name', $roleName)
                ->where('guard_name', $this->guard_name)
                ->first();

            if ($role) {
                $this->assignRole($role);
            } else {
                \Log::warning("Role '{$roleName}' does not exist. User ID: {$this->id}");
            }
        }
    }

    /**
     * Automatically assign roles on user creation
     */
    protected static function booted()
    {
        static::created(function (User $user) {
            if ($user->id === 1) {
                // First user = super admin
                $user->assignDefaultRole('super_administrator');
            } else {
                // All other users = administrator by default
                $user->assignDefaultRole('administrator');
            }
        });
    }
}
