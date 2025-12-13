<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wisata');
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('kategori');
            $table->decimal('harga_tiket', 10, 2);
            $table->integer('kapasitas_harian')->default(100);
            $table->string('gambar')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->foreignId('pengelola_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};