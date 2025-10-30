<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('username', 'like', '%' . $search . '%');
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnBarang::class);
    }

    public function isAdmin()
    {
        return $this->role === 'ADMIN';
    }

    public function isManajer()
    {
        return $this->role === 'MANAJER';
    }

    public function isAdminOrManajer()
    {
        return $this->role === 'ADMIN' || $this->role === 'MANAJER';
    }

    public function isKasir()
    {
        return $this->role === 'KASIR';
    }
}
