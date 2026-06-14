<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('token', 512)->unique();
            $table->string('platform')->nullable();
            $table->timestamps();

            $table->index('username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};
