<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramItem extends Model
{
    protected $table = 'program_items';
    protected $primaryKey = 'program_item_id';
    public $timestamps = false;

    protected $fillable = ['program_id', 'exercise_name', 'duration_minutes', 'intensity_level'];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }
}