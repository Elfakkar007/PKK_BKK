<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('company_type'); // PT/CV/UD
            $table->string('industry_sector');
            $table->text('description')->nullable();
            $table->text('head_office_address');
            $table->text('branch_addresses')->nullable(); // JSON
            $table->string('pic_name'); // Person in Charge
            $table->string('pic_phone');
            $table->string('pic_email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('legality_doc')->nullable(); // SIUP/NIB PDF
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};