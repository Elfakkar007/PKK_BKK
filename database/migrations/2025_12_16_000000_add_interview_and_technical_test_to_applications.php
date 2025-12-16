<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL: Drop the old CHECK constraint first
        DB::statement("ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_status_check");

        // Then add the new CHECK constraint with all values including interview and technical_test
        DB::statement("ALTER TABLE applications ADD CONSTRAINT applications_status_check CHECK (status IN ('pending', 'reviewed', 'interview', 'technical_test', 'accepted', 'rejected'))");
    }

    public function down(): void
    {
        // Revert: Drop the new constraint and recreate the old one
        DB::statement("ALTER TABLE applications DROP CONSTRAINT IF EXISTS applications_status_check");
        DB::statement("ALTER TABLE applications ADD CONSTRAINT applications_status_check CHECK (status IN ('pending', 'reviewed', 'accepted', 'rejected'))");
    }
};
