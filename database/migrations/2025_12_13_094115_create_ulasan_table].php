<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wisatawan_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('wisata_id')->constrained('wisata')->onDelete('cascade');
            $table->integer('rating')->default(5);
            $table->text('komentar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};