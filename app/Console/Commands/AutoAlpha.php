<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AutoAlpha extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absen:auto-alpha';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis set status Alpha untuk siswa yang belum absen jam 08:00';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();

        // 1. Ambil semua NIS siswa
        $allSiswa = DB::table('siswas')->pluck('nis');

        // 2. Ambil semua NIS yang sudah absen hari ini
        $absenHariIni = DB::table('data_absensi')
            ->whereDate('tanggal', $today)
            ->pluck('nis');

        // 3. Siswa yang belum absen
        $belumAbsen = $allSiswa->diff($absenHariIni);

        // 4. Insert status Alpha
        foreach ($belumAbsen as $nis) {
            DB::table('data_absensi')->insert([
                'nis' => $nis,
                'tanggal' => $today,
                'jam_masuk' => null,
                'status' => 'Alpha',
                'keterangan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->info('Absensi Alpha otomatis berhasil.');
    }
}
