<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('nis')->unique();
            $table->enum('gender', ['male', 'female']);
            $table->enum('status', ['student', 'alumni']);
            $table->year('graduation_year')->nullable();
            $table->string('class')->nullable();
            $table->string('major'); // Jurusan
            $table->date('birth_date');
            $table->string('birth_place');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('cv_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};