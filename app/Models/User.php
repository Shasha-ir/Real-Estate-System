<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // --- Relationships ---
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Seller's listings (your existing FK is properties.user_id)
    public function properties()
    {
        return $this->hasMany(Property::class, 'user_id');
    }

    // Buyer's reservations (FK is reservations.buyer_id)
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'buyer_id');
    }

    // --- Helpers ---
    public function hasRole(string $name): bool
    {
        return optional($this->role)->name === $name;
    }

    /**
     * Mass assignable attributes.
     * Keep role_id/custom_id/username as you’re using them in this project.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'custom_id',
        'username',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
