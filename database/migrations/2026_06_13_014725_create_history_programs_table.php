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
        Schema::create('history_programs', function (Blueprint $table) {
            $table->id();
            $table->integer('history_id');
            $table->integer('program_id');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('history_id')->references('history_id')->on('histories')->onDelete('cascade');
            $table->foreign('program_id')->references('program_id')->on('programs')->onDelete('cascade');
        });

        // Migrate existing data from histories
        \DB::table('histories')->whereNotNull('program_id')->orderBy('history_id')->chunk(100, function ($histories) {
            $inserts = [];
            foreach ($histories as $h) {
                $inserts[] = [
                    'history_id' => $h->history_id,
                    'program_id' => $h->program_id,
                    'created_at' => \Carbon\Carbon::parse($h->date)->toDateTimeString()
                ];
            }
            \DB::table('history_programs')->insert($inserts);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_programs');
    }
};
