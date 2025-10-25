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
        Schema::create('food_consumptions', function (Blueprint $table) {
            $table->integer('history_id');
            $table->integer('food_id')->index('fk_foodconsum_food');
            $table->integer('portions')->nullable()->default(1);
            $table->enum('category', ['breakfast', 'lunch', 'dinner', 'snack'])->nullable()->default('lunch');

            $table->primary(['history_id', 'food_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_consumptions');
    }
};
