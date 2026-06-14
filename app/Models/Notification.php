<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    protected $table = 'notifications';
    protected $primaryKey = 'id_notification';

    protected $fillable = [
        'username',
        'message',
        'notify_at',
        'is_read',
        'title',
        'type',
        'icon'
    ];

    protected $casts = [
        'notify_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'username', 'username');
    }
}
