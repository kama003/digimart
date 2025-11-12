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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'seller', 'admin'])->default('customer')->after('email_verified_at');
            $table->decimal('balance', 10, 2)->default(0)->after('role');
            $table->boolean('is_banned')->default(false)->after('balance');
            $table->index('role');
            $table->index('is_banned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_banned']);
            $table->dropColumn(['role', 'balance', 'is_banned']);
        });
    }
};
