<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $table = 'user';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['email', 'password', 'role', 'username'];

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
    
    public function isClient(): bool
    {
        return (int) $this->role === 1;
    }

    public function isTrainer(): bool
    {
        return (int) $this->role === 2;
    }

    public function verifyPassword(string $password): bool
    {
        return $this->password === $password;
    }

    public static function authenticate(string $username, string $password): ?self
    {
        $user = self::where('username', $username)->first();

        if ($user && $user->verifyPassword($password)) {
            return $user;
        }

        return null;
    }
}