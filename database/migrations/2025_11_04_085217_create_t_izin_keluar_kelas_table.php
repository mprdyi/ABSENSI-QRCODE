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
        Schema::create('t_izin_keluar_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nis');
            $table->string('kode_kelas');
            $table->string('waktu_izin');
            $table->string('waktu_habis');
            $table->string('keperluan');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_izin_keluar_kelas');
    }
};
