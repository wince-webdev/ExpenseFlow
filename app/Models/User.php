<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // ← AJOUTER CETTE LIGNE

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles; // ← AJOUTER CE TRAIT pour les rôles

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELATION : Un user a plusieurs dépenses
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // RELATION : Un user a plusieurs revenues
    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }
}