<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class AdminUser extends Authenticatable
{
    use HasFactory;
    
    protected $guard = 'admin';

    protected $table = 'admin_users'; // Match your database table

    protected $fillable = ['name', 'email', 'password', 'last_login_at', 'last_logout_at', 'is_online'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // Removed 'password' => 'hashed' to prevent double-hashing issues
        'last_login_at' => 'datetime',
        'last_logout_at' => 'datetime',
        'is_online' => 'boolean',
    ];
    
    /**
     * Hash the password when setting it
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}