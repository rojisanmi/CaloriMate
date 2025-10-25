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
        Schema::create('client', function (Blueprint $table) {
            $table->string('username', 20)->primary();
            $table->decimal('tb', 5)->nullable();
            $table->decimal('bb', 5)->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->integer('umur')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
