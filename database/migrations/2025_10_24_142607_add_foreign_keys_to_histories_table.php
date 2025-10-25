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
        Schema::table('histories', function (Blueprint $table) {
            $table->foreign(['program_id'], 'fk_program')->references(['program_id'])->on('programs')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['username'], 'fk_username')->references(['username'])->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropForeign('fk_program');
            $table->dropForeign('fk_username');
        });
    }
};
