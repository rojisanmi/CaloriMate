<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryProgram extends Model
{
    protected $table = 'history_programs';
    public $timestamps = false; // To keep it simple, or keep them if needed. Let's keep them to track when exactly the user did the program.
    protected $fillable = ['history_id', 'program_id'];

    public function history()
    {
        return $this->belongsTo(History::class, 'history_id', 'history_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }
}
