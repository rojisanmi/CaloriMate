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
        Schema::create('histories', function (Blueprint $table) {
            $table->integer('history_id', true);
            $table->string('username', 20)->index('fk_username');
            $table->integer('program_id')->nullable()->index('fk_program');
            $table->date('date');
            $table->decimal('calori_in')->nullable()->default(0);
            $table->decimal('calori_out')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
