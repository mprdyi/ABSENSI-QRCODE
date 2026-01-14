<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;

class GoogleDriveService
{
    public static function client($jsonPath)
    {
        $client = new Client();
        $client->setAuthConfig($jsonPath);
        $client->addScope(Drive::DRIVE);
        $client->setAccessType('offline');

        return new Drive($client);
    }
}
