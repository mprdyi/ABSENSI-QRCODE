<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\AutoAlpha; // <- tambahkan ini

class Kernel extends ConsoleKernel
{
    /**
     * Daftar command yang harus diregistrasi.
     *
     * @var array
     */
    protected $commands = [
        AutoAlpha::class, // <- pastikan ini ada
    ];

    /**
     * Definisikan jadwal task/command.
     */
    protected function schedule(Schedule $schedule)
    {
         // Jalankan otomatis setiap Senin–Jumat jam 07:50
            $schedule->command('absen:auto-alpha')
            ->weekdays()
            ->dailyAt('07:15')
            ->appendOutputTo(storage_path('logs/cron.log'));

        // Kalau mau test, aktifkan ini:
        // $schedule->command('absen:auto-alpha')->everyMinute();
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
