<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;
    
    public $timestamps = false;
    protected $table = 'user';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['email', 'password', 'role', 'username'];

    protected $hidden = [
        'password',
    ];

    // Relasi dengan model Client 
    public function client()
    {
        return $this->hasOne(Client::class, 'username', 'username');
    }

    // Relasi dengan model Trainer
    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'username', 'username');
    }

    // Relasi dengan model History
    public function histories()
    {
        return $this->hasMany(History::class, 'username', 'username');
    }
    
    // Role 1 untuk client
    public function isClient(): bool
    {
        return (int) $this->role === 1;
    }

    // Role 2 untuk trainer
    public function isTrainer(): bool
    {
        return (int) $this->role === 2;
    }

    // Verifikasi password
    public function verifyPassword(string $password): bool
    {
        return $this->password === $password;
    }

    // Autentikasi user
    public static function authenticate(string $username, string $password): ?self
    {
        $user = self::where('username', $username)->first();

        if ($user && $user->verifyPassword($password)) {
            return $user;
        }

        return null;
    }

    // Aksesor untuk mendapatkan label peran
    public function getRoleLabelAttribute()
    {
        return match((int) $this->role) {
            1 => 'Client',
            2 => 'Trainer',
            default => 'Unknown',
        };
    }
}