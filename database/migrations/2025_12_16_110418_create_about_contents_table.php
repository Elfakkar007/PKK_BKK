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
        Schema::create('about_contents', function (Blueprint $table) {
            $table->id();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->json('work_programs')->nullable();
            $table->string('organization_chart')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Run the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_contents');
    }
};