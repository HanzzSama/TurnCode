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
        Schema::create('user_daily_missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('mission_key');
            $table->integer('progress')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->date('date');
            $table->timestamps();
            
            // Limit to one record per user per mission per day
            $table->unique(['user_id', 'mission_key', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_daily_missions');
    }
};
