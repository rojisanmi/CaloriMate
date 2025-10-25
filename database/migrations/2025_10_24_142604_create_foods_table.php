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
        Schema::create('foods', function (Blueprint $table) {
            $table->integer('food_id', true);
            $table->string('name', 100);
            $table->decimal('grammage', 5);
            $table->decimal('calories_per_portion', 6);
            $table->decimal('total_fat', 5)->nullable();
            $table->decimal('total_carbo', 5)->nullable();
            $table->decimal('total_protein', 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
