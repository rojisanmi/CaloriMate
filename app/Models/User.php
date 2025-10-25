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
}