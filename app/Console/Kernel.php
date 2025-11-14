<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\AutoAlpha;
use App\Console\Commands\SendLateReport;
use App\Models\ProfilSekolah;

class Kernel extends ConsoleKernel
{
    /**
     * Daftar command yang harus diregistrasi.
     *
     * @var array
     */
    protected $commands = [
        AutoAlpha::class,
    ];

    /**
     * Definisikan jadwal task/command.
     */
    protected function schedule(Schedule $schedule)
    {

          // Ambil jam dinamis dari database
            $profil = ProfilSekolah::first();

            $jamAutoAlpa = substr($profil->auto_alpa ?? '07:15', 0, 5);
            $jamNotifWA  = substr($profil->notif_wa ?? '07:20', 0, 5);

          // AUTO ALPHA → jalan duluan
            $schedule->command('absen:auto-alpha')
            ->weekdays()
            ->dailyAt($jamAutoAlpa)
            ->appendOutputTo(storage_path('logs/cron.log'));

        // KIRIM WA TERLAMBAT → jeda 5 menit
            $schedule->command('absen:kirim-terlambat')
            ->weekdays()
            ->dailyAt($jamNotifWA)
            ->appendOutputTo(storage_path('logs/cron.log'));
        }

    /**
     * Daftarkan command aplikasi.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
