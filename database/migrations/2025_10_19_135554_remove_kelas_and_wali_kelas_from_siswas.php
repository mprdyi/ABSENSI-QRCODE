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
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['wali_kelas', 'kelas']);

        // tambah kolom baru untuk relasi ke data_kelas
        $table->unsignedBigInteger('id_kelas')->after('nama')->nullable();

        // (opsional) tambahkan foreign key ke tabel data_kelas
        $table->foreign('id_kelas')->references('id')->on('data_kelas')->onDelete('cascade');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('wali_kelas')->nullable();
            $table->string('kelas')->nullable();
            $table->dropForeign(['id_kelas']);
            $table->dropColumn('id_kelas');
        });
    }
};
