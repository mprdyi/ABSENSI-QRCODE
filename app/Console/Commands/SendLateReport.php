<?php
/*
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

*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Carbon\Carbon;
use App\Services\FonnteService;
use Illuminate\Support\Facades\DB;

class SendLateReport extends Command
{
    protected $signature = 'absen:kirim-terlambat';
    protected $description = 'Kirim laporan absensi siswa ke wali kelas';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        // Urutan status
        $statusUrut = ['Terlambat', 'Sakit', 'Izin', 'Alpha'];

        // Ambil absensi hari ini
        $absensi = Absensi::with('siswa.kelas.waliKelas')
            ->whereDate('tanggal', $today)
            ->whereIn('status', $statusUrut)
            ->get()
            ->groupBy(fn($item) => $item->siswa->kelas->id);

        if ($absensi->isEmpty()) {
            $this->info("Tidak ada data absensi untuk hari ini.");
            return;
        }

        foreach ($absensi as $kelasId => $list) {

            $kelas = $list->first()->siswa->kelas;
            $wali  = $kelas->waliKelas;

            $noWa = $wali->no_hp ?? null;
            if (!$noWa) {
                $this->error("Nomor WA wali kelas {$kelas->kelas} tidak ditemukan.");
                continue;
            }

            // === HEADER PESAN ===
            $pesan  = "*Rekap Absensi Hari Ini*\n";
            $pesan .= "Wali Kelas: {$wali->nama_guru}\n";
            $pesan .= "Kelas: {$kelas->kelas}\n";
            $pesan .= "Tanggal: " . Carbon::today()->format('d-m-Y') . "\n\n";

            $totalTidakHadir = 0;

            // === LOOP STATUS SESUAI URUTAN ===
            foreach ($statusUrut as $status) {

                $filtered = $list->where('status', $status);

                if ($filtered->isEmpty()) continue;

                $pesan .= "*{$status}*\n";
                $no = 1;

                foreach ($filtered as $item) {

                    // --- Terlambat → tambah keterangan (menit) ---
                    if ($status === 'Terlambat') {
                        $ket = $item->keterangan ? " ({$item->keterangan})" : "";
                        $pesan .= "{$no}. {$item->siswa->nama}{$ket}\n";
                    }
                    else {
                        // Sakit, Izin, Alpha → tanpa keterangan
                        $pesan .= "{$no}. {$item->siswa->nama}\n";
                        $totalTidakHadir++; // dihitung tidak hadir
                    }

                    $no++;
                }

                $pesan .= "\n";
            }

            // === TOTAL TIDAK HADIR ===
            $pesan .= "Total Tidak Hadir: *{$totalTidakHadir}* siswa.";

            // === KIRIM WA ===
            FonnteService::send($noWa, $pesan);

            $this->info("WA terkirim ke wali kelas {$kelas->kelas} → {$wali->nama_guru}");
        }

        DB::disconnect();
    }
}
