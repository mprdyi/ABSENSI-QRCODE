<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AutoAlpha extends Command
{
    /**
     * Nama command yang bisa dijalankan lewat terminal.
     *
     * @var string
     */
    protected $signature = 'absen:auto-alpha';

    /**
     * Deskripsi command ini.
     *
     * @var string
     */
    protected $description = 'Otomatis memberi status Alpa untuk siswa yang belum absen pada jam tertentu';

    /**
     * Jalankan command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();

        // 1️⃣ Cek apakah hari ini sudah ada absen (berarti bukan hari libur)
        $adaAbsenHariIni = DB::table('data_absensi')
            ->whereDate('tanggal', $today)
            ->whereIn('status', ['Hadir', 'Terlambat'])
            ->exists();

        if (!$adaAbsenHariIni) {
            $this->info('Tidak dijalankan karena belum ada absensi hari ini (mungkin hari libur).');
            return;
        }

        // 2️⃣ Ambil semua siswa
        $allSiswa = DB::table('siswas')->pluck('nis');

        // 3️⃣ Ambil siswa yang sudah absen hari ini
        $sudahAbsen = DB::table('data_absensi')
            ->whereDate('tanggal', $today)
            ->pluck('nis');

        // 4️⃣ Bedakan siapa yang belum absen
        $belumAbsen = $allSiswa->diff($sudahAbsen);

        if ($belumAbsen->isEmpty()) {
            $this->info('Semua siswa sudah absen, tidak ada yang di-Alpa.');
            return;
        }

        // 5️⃣ Insert otomatis status Alpa
        foreach ($belumAbsen as $nis) {
            DB::table('data_absensi')->insert([
                'nis' => $nis,
                'tanggal' => $today,
                'jam_masuk' => now()->format('H:i:s'),
                'status' => 'Alpha',
                'keterangan' => 'Tidak hadir',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info('✅ Otomatis Alpa berhasil dimasukkan untuk siswa yang belum absen.');
    }
}
