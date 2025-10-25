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
}
