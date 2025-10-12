<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('jurusan')->nullable()->change(); // pastikan dulu tipe sesuai
            $table->renameColumn('jurusan', 'jk'); // ganti nama kolom
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->renameColumn('jk', 'jurusan');
        });
    }
};
