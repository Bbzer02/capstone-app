<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('password');
            $table->timestamp('last_logout_at')->nullable()->after('last_login_at');
            $table->boolean('is_online')->default(false)->after('last_logout_at');
        });
    }

    public function down(): void
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_logout_at', 'is_online']);
        });
    }
};


