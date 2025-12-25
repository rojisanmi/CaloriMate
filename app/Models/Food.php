<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';
    protected $primaryKey = 'food_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'grammage',
        'calories_per_portion',
        'total_fat',
        'total_carbo',
        'total_protein',
    ];
    // Relasi ke tabel food_consumptions
    public function foodConsumptions()
    {
        return $this->hasMany(FoodConsumption::class, 'food_id', 'food_id');
    }

    // Hitung kalori berdasarkan porsi dalam gram
    public function calculateCalories(float $portionGrams): float
    {
        if ($this->grammage <= 0)
            return 0;
        return round(($this->calories_per_portion / $this->grammage) * $portionGrams, 2);
    }
}
