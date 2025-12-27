<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'program_id';
    public $timestamps = false;

    protected $fillable = ['name', 'type', 'difficulty', 'duration_minutes'];

    public function getTotalDurationAttribute()
    {
        return $this->items->sum('duration_minutes');
    }
    // Relasi ke tabel program_items
    // Setiap program memiliki banyak program items
    public function items()
    {
        return $this->hasMany(ProgramItem::class, 'program_id', 'program_id');
    }
}
