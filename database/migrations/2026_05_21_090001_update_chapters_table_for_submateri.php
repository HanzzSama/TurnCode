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
        Schema::table('chapters', function (Blueprint $table) {
            // First drop the old foreign key & column
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
            
            // Then add the new column and foreign key
            $table->foreignId('submateri_id')->after('id')->constrained('submateris')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign(['submateri_id']);
            $table->dropColumn('submateri_id');
            $table->foreignId('course_id')->after('id')->constrained()->cascadeOnDelete();
        });
    }
};
