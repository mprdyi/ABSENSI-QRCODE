<?php

namespace App\Services;

use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Visibility;

class GoogleDriveAdapter implements FilesystemAdapter
{
    protected $service;
    protected $folderId;

    public function __construct(Drive $service, $folderId = null)
    {
        $this->service = $service;
        $this->folderId = $folderId;
    }

    /** WRITE FILE */
    public function write(string $path, string $contents, Config $config): void
    {
        $file = new DriveFile([
            'name' => basename($path),
            'parents' => $this->folderId ? [$this->folderId] : []
        ]);

        $this->service->files->create($file, [
            'data' => $contents,
            'uploadType' => 'multipart'
        ]);
    }

    public function writeStream(string $path, $resource, Config $config): void
    {
        $this->write($path, stream_get_contents($resource), $config);
    }

    /** REQUIRED BY FLYSYSTEM v3 */
    public function fileExists(string $path): bool
    {
        return false; // belum dipakai
    }

    public function directoryExists(string $path): bool
    {
        return false; // belum dipakai
    }

    public function delete(string $path): void {}
    public function deleteDirectory(string $path): void {}
    public function createDirectory(string $path, Config $config): void {}
    public function setVisibility(string $path, string $visibility): void {}

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path);
    }

    public function read(string $path): string
    {
        return '';
    }

    public function readStream(string $path)
    {
        return null;
    }

    public function mimeType(string $path): FileAttributes
    {
        return new FileAttributes($path);
    }

    public function lastModified(string $path): FileAttributes
    {
        return new FileAttributes($path);
    }

    public function fileSize(string $path): FileAttributes
    {
        return new FileAttributes($path);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        return [];
    }

    public function move(string $source, string $destination, Config $config): void {}
    public function copy(string $source, string $destination, Config $config): void {}
}
