<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Allow admins to omit requirement fields
        DB::statement('ALTER TABLE jobs MODIFY education_requirements TEXT NULL');
        DB::statement('ALTER TABLE jobs MODIFY eligibility_requirements TEXT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ensure null values are converted to empty strings before reverting
        DB::table('jobs')
            ->whereNull('education_requirements')
            ->update(['education_requirements' => '']);

        DB::table('jobs')
            ->whereNull('eligibility_requirements')
            ->update(['eligibility_requirements' => '']);

        DB::statement('ALTER TABLE jobs MODIFY education_requirements TEXT NOT NULL');
        DB::statement('ALTER TABLE jobs MODIFY eligibility_requirements TEXT NOT NULL');
    }
};

