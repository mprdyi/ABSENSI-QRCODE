<?php

namespace App\Providers;
use Storage;
use Masbug\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
   {
    Storage::extend('google', function ($app, $config) {
        $client = new \Google_Client();
        $client->setClientId($config['clientId']);
        $client->setClientSecret($config['clientSecret']);
        $client->refreshToken($config['refreshToken']);

        $service = new \Google_Service_Drive($client);

        $adapter = new GoogleDriveAdapter($service, $config['folderId'] ?? null);

        return new \Illuminate\Filesystem\FilesystemAdapter(
            new Filesystem($adapter),
            $adapter,
            $config
        );
    });

}

}
