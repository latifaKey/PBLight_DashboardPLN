<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan tabel users ada
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('karyawan');
                $table->rememberToken();
                $table->timestamps();
            });
        }
        // Cek apakah kolom role sudah ada di tabel users
        else if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('karyawan')->after('password');
            });
        }
        // Pastikan semua role memiliki nilai default
        else {
            $this->updateEmptyRoles();
        }
    }

    /**
     * Update empty roles to default 'karyawan'
     */
    private function updateEmptyRoles()
    {
        DB::table('users')
            ->whereNull('role')
            ->orWhere('role', '')
            ->update(['role' => 'karyawan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak melakukan operasi drop kolom karena ini adalah kolom penting
    }
};
