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
        Schema::table('client', function (Blueprint $table) {
            $table->decimal('calorie_target', 7, 2)->nullable()->after('umur');
            $table->tinyInteger('protein_ratio')->default(30)->after('calorie_target');
            $table->tinyInteger('carbo_ratio')->default(40)->after('protein_ratio');
            $table->tinyInteger('fat_ratio')->default(30)->after('carbo_ratio');
        });
    }

    public function down(): void
    {
        Schema::table('client', function (Blueprint $table) {
            $table->dropColumn(['calorie_target', 'protein_ratio', 'carbo_ratio', 'fat_ratio']);
        });
    }
};
