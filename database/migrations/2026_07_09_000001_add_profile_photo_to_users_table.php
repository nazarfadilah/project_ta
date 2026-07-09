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
            $table->string('profile_photo', 255)->nullable()->after('phone');
            $table->text('blocked_reason')->nullable()->after('status');
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'SUSPENDED', 'SUSPENDED_PERMANENT'])->default('ACTIVE')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'blocked_reason']);
            // To revert change of enum column back to original we need to do change() again
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'SUSPENDED'])->default('ACTIVE')->change();
        });
    }
};
