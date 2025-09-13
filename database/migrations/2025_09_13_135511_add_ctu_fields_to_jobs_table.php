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
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('item_number')->nullable()->after('title');
            $table->string('campus')->after('item_number');
            $table->integer('vacancies')->default(1)->after('campus');
            $table->text('education_requirements')->after('vacancies');
            $table->text('experience_requirements')->nullable()->after('education_requirements');
            $table->text('training_requirements')->nullable()->after('experience_requirements');
            $table->text('eligibility_requirements')->after('training_requirements');
            $table->string('email_subject_format')->nullable()->after('eligibility_requirements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn([
                'item_number',
                'campus',
                'vacancies',
                'education_requirements',
                'experience_requirements',
                'training_requirements',
                'eligibility_requirements',
                'email_subject_format'
            ]);
        });
    }
};
