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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            // relasi ke siswa (pakai NIS, bukan ID)
            $table->unsignedBigInteger('nis'); //  tipe integer tanpa minus
            $table->foreign('nis')->references('nis')->on('siswas')->onDelete('cascade');

            // kolom absensi
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha', 'Terlambat']);
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
