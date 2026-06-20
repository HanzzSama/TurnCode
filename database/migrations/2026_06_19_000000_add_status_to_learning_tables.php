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
        Schema::table('submateris', function (Blueprint $table) {
            $table->string('status')->default('published'); // published, draft, coming_soon
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->string('status')->default('published'); // published, draft, coming_soon
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->string('status')->default('published'); // published, draft, coming_soon
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submateris', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
