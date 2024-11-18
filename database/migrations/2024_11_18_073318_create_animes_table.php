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
    Schema::create('animes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('mal_id')->unique();
        $table->string('title');
        $table->string('slug');
        $table->text('synopsis')->nullable();
        $table->string('image_url')->nullable();
        $table->integer('episodes')->nullable();
        $table->string('type')->nullable();
        $table->double('score')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
