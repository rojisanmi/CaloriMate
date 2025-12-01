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
    public function foodConsumptions()
    {
        return $this->hasMany(FoodConsumption::class, 'food_id', 'food_id');
    }

    public function calculateCalories(float $portionGrams): float
    {
        if ($this->grammage <= 0)
            return 0;
        return round(($this->calories_per_portion / $this->grammage) * $portionGrams, 2);
    }
}
