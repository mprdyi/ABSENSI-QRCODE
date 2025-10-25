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
        Schema::create('data_absensi', function (Blueprint $table) {
            $table->id();
            // relasi ke siswa (pakai NIS, bukan ID)
            $table->unsignedBigInteger('nis'); //  tipe integer tanpa minus
            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade');

            // relasi ke kelas
            $table->foreignId('id_kelas')->constrained('data_kelas')->onDelete('cascade');

            // relasi ke guru (wali kelas)
            $table->foreignId('id_wali_kelas')->constrained('gurus')->onDelete('cascade');

            // kolom absensi
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha']);
            $table->string('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
