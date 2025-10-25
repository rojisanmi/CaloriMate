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
        Schema::table('food_consumptions', function (Blueprint $table) {
            $table->foreign(['food_id'], 'fk_foodconsum_food')->references(['food_id'])->on('foods')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['history_id'], 'fk_foodconsum_history')->references(['history_id'])->on('histories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_consumptions', function (Blueprint $table) {
            $table->dropForeign('fk_foodconsum_food');
            $table->dropForeign('fk_foodconsum_history');
        });
    }
};
