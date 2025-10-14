<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('data_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('id_wali_kelas')->unique();
            $table->string('kelas');
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kelas');
    }
};
