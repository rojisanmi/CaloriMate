<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $table = 'device_tokens';

    protected $fillable = [
        'username',
        'token',
        'platform',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'username', 'username');
    }
}
