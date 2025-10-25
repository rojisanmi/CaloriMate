<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_items', function (Blueprint $table) {
            $table->foreign(['program_id'], 'fk_programitems_program')->references(['program_id'])->on('programs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_items', function (Blueprint $table) {
            $table->dropForeign('fk_programitems_program');
        });
    }
};
