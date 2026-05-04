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
    protected $fillable = ['username', 'nama', 'keahlian', 'sertifikasi', 'photo_path'];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    // Create program
    public function createProgram(array $data): Program
    {
        return Program::create($data);
    }

    // Update program
    public function updateProgram(Program $program, array $data): bool
    {
        return $program->update($data);
    }

    // Delete program
    public function deleteProgram(Program $program): bool
    {
        return $program->delete();
    }

    // Create food
    public function createFood(array $data): Food
    {
        return Food::create($data);
    }

    // Update food
    public function updateFood(Food $food, array $data): bool
    {
        return $food->update($data);
    }

    // Delete food
    public function deleteFood(Food $food): bool
    {
        return $food->delete();
    }
}
