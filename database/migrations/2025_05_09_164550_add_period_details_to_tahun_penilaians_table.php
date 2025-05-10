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
        Schema::table('tahun_penilaians', function (Blueprint $table) {
            $table->enum('tipe_periode', ['tahunan', 'semesteran', 'triwulanan', 'bulanan'])
                  ->default('tahunan')
                  ->after('deskripsi');
            $table->date('tanggal_mulai')->nullable()->after('tipe_periode');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->boolean('is_locked')->default(false)->after('is_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahun_penilaians', function (Blueprint $table) {
            $table->dropColumn(['tipe_periode', 'tanggal_mulai', 'tanggal_selesai', 'is_locked']);
        });
    }
};
