<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    public $timestamps = false;
    protected $table = 'trainers';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['username', 'nama', 'keahlian', 'sertifikasi'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    // Business Logic Methods
    public function createProgram(array $data): Program
    {
        return Program::create($data);
    }

    public function updateProgram(Program $program, array $data): bool
    {
        return $program->update($data);
    }

    public function deleteProgram(Program $program): bool
    {
        return $program->delete();
    }

    public function createFood(array $data): Food
    {
        return Food::create($data);
    }

    public function updateFood(Food $food, array $data): bool
    {
        return $food->update($data);
    }

    public function deleteFood(Food $food): bool
    {
        return $food->delete();
    }
}
