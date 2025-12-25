<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodConsumption extends Model
{
    protected $table = 'food_consumptions';
    protected $primaryKey = 'food_consumption_id';
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'history_id',
        'food_id',
        'portions',
        'category',
    ];

    protected $casts = [
        'portions' => 'integer',
    ];

    // Relasi ke tabel history  
    public function history()
    {
        return $this->belongsTo(History::class, 'history_id', 'history_id');
    }

    // Relasi ke tabel food
    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id', 'food_id');
    }

    // Hitung total kalori dari konsumsi makanan ini
    public function getTotalCalories(): float
    {
        return $this->food->calories_per_portion * $this->portions;
    }
}
