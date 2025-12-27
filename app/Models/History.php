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

    // Relasi ke tabel client
    public function client()
    {
        return $this->belongsTo(Client::class, 'username', 'username');
    }

    // Relasi ke tabel program
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    // Relasi ke tabel food_consumptions
    public function foodConsumptions()
    {
        return $this->hasMany(FoodConsumption::class, 'history_id', 'history_id');
    }

    // Hitung total kalori yang dikonsumsi berdasarkan food consumptions
    public function getTotalCaloriesConsumed(): float
    {
        return $this->foodConsumptions->sum(function ($consumption) {
            return $consumption->food->calories_per_portion * $consumption->portions;
        });
    }
}
