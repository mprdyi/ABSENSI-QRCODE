<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Services\FonnteService;

class SendLateReport extends Command
{
    protected $signature = 'absen:kirim-terlambat';
    protected $description = 'Kirim laporan siswa terlambat ke wali kelas per kelas';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        // === Ambil semua siswa terlambat dengan relasi lengkap ===
        $terlambat = Absensi::with('siswa.kelas.waliKelas')
            ->whereDate('tanggal', $today)
            ->where('status', 'Terlambat')
            ->get()
            ->groupBy(fn($item) => $item->siswa->kelas->id);

        if ($terlambat->isEmpty()) {
            $this->info("Tidak ada siswa terlambat hari ini.");
            return;
        }

        foreach ($terlambat as $kelasId => $list) {

            $kelas = $list->first()->siswa->kelas;
            $wali  = $kelas->waliKelas;


            $noWa = $wali->no_hp ?? null;

            if (!$noWa) {
                $this->error("No WA wali kelas untuk kelas {$kelas->kelas} tidak ditemukan.");
                continue;
            }

            // === Format pesan WA ===
            $pesan  = " *Rekap Siswa Terlambat Hari Ini*\n";
            $pesan .= "Kelas: *{$kelas->kelas}*\n";
            $pesan .= "Tanggal: " . Carbon::today()->format('d-m-Y') . "\n\n";
            $no = 1;
            foreach ($list as $item) {
                $pesan .= "{$no}. {$item->siswa->nama} ({$item->keterangan})\n";
                $no++;
            }

            $pesan .= "\nTotal: *" . count($list) . "* siswa terlambat.";

            // === Kirim WA ===
            FonnteService::send($noWa, $pesan);

            $this->info("WA terkirim ke wali kelas {$kelas->kelas} → {$wali->nama_guru}");
        }
    }
}
