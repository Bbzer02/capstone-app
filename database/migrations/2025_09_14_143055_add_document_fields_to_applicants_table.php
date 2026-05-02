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
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('cover_letter_path')->nullable();
            $table->string('transcript_path')->nullable();
            $table->string('certificate_path')->nullable();
            $table->string('portfolio_path')->nullable();
            $table->json('additional_documents')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'cover_letter_path', 
                'transcript_path',
                'certificate_path',
                'portfolio_path',
                'additional_documents'
            ]);
        });
    }
};
