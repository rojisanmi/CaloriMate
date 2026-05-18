<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $table = 'user';

    protected $primaryKey = 'username';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // =========================
    // RELATIONSHIPS
    // =========================

    public function client()
    {
        return $this->hasOne(Client::class, 'username', 'username');
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'username', 'username');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'username', 'username');
    }

    // =========================
    // ROLE CHECKERS
    // =========================

    public function isAdmin(): bool
    {
        return (int) $this->role === 0;
    }

    public function isClient(): bool
    {
        return (int) $this->role === 1;
    }

    public function isTrainer(): bool
    {
        return (int) $this->role === 2;
    }

    // =========================
    // ACCESSORS
    // =========================

    public function getRoleLabelAttribute(): string
    {
        return match ((int) $this->role) {
            1 => 'Client',
            2 => 'Trainer',
            default => 'Unknown',
        };
    }
}