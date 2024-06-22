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
        Schema::create('tiktok_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id')->constrained('feeds')->onDelete('cascade');
            $table->string('name');
            $table->integer('fan_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_sources');
    }
};
