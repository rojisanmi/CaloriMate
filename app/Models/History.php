<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories';
    protected $primaryKey = 'history_id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'program_id',
        'date',
        'calori_in',
        'calori_out',
    ];

    protected $casts = [
        'date' => 'date',
        'calori_in' => 'decimal:2',
        'calori_out' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'username', 'username');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function foodConsumptions()
    {
        return $this->hasMany(FoodConsumption::class, 'history_id', 'history_id');
    }

    public function getTotalCaloriesConsumed(): float
    {
        return $this->foodConsumptions->sum(function ($consumption) {
            return $consumption->food->calories_per_portion * $consumption->portions;
        });
    }
}
