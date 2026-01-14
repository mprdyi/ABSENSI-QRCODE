<?php

namespace App\Providers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Google\Service\Drive;
use App\Services\GoogleDriveService;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('google', function ($app, $config) {

            $service = GoogleDriveService::client(
                $config['service_account_json']
            );

            return new Filesystem(
                new \App\Services\GoogleDriveAdapter($service, $config['folderId'])
            );
        });
    }
}
