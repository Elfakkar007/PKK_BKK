<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode jurusan (TKJ, RPL, dll)
            $table->string('name'); // Nama lengkap jurusan
            $table->text('description')->nullable(); // Deskripsi jurusan
            $table->boolean('is_active')->default(true); // Status aktif/nonaktif
            $table->integer('order')->default(0); // Urutan tampilan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};