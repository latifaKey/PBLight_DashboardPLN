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
        Schema::create('nilai_riwayat_kpi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nilai_kpi_id')->nullable();
            $table->unsignedBigInteger('indikator_id');
            $table->unsignedInteger('tahun');
            $table->unsignedTinyInteger('bulan');
            $table->enum('periode_tipe', ['bulanan', 'triwulan', 'semester', 'tahunan'])->default('bulanan');
            $table->decimal('nilai', 10, 2)->default(0);
            $table->decimal('target', 10, 2)->default(0);
            $table->decimal('persentase', 5, 2)->default(0); // Persentase pencapaian
            $table->boolean('diverifikasi')->default(false);
            $table->unsignedBigInteger('finalisasi_oleh')->nullable();
            $table->timestamp('finalisasi_tanggal')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('file_bukti')->nullable();
            $table->timestamps();

            // Indeks dan foreign keys
            $table->foreign('nilai_kpi_id')->references('id')->on('nilai_kpi')->onDelete('set null');
            $table->foreign('indikator_id')->references('id')->on('indikator');
            $table->foreign('finalisasi_oleh')->references('id')->on('users')->onDelete('set null');

            // Tambahkan indeks untuk mempercepat query
            $table->index(['tahun', 'bulan', 'periode_tipe']);
            $table->index(['indikator_id', 'tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_riwayat_kpi');
    }
};
