<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fokus', function (Blueprint $table) {
            $table->id();
            $table->string('interest_val');
            $table->string('val')->unique();
            $table->string('name');
            $table->text('desc')->nullable();
            $table->text('icon')->nullable();
            $table->text('tags')->nullable(); // Comma-separated list or JSON
            $table->timestamps();

            // Set up a foreign key or relationship if needed
            $table->foreign('interest_val')->references('val')->on('interests')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fokus');
    }
};
