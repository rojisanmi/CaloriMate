<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('fat_ratio');
        });

        Schema::table('trainers', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('keahlian');
        });
    }

    public function down(): void
    {
        Schema::table('client', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });

        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
