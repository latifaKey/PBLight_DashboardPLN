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
            $table->string('profile_photo')->nullable()->after('role'); // Kolom untuk menyimpan path foto profil
            $table->string('phone')->nullable()->after('profile_photo'); // Nomor telepon user
            $table->text('bio')->nullable()->after('phone'); // Bio singkat user
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'phone', 'bio']);
        });
    }
};
