<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('type')->default('text')->after('lesson_id'); // 'text', 'code', 'puzzle'
            $table->string('image_url')->nullable()->after('question');
            $table->string('video_url')->nullable()->after('image_url');
            $table->text('code_block')->nullable()->after('video_url');
            $table->text('correct_answer')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('correct_answer', 255)->change();
            $table->dropColumn(['type', 'image_url', 'video_url', 'code_block']);
        });
    }
};
