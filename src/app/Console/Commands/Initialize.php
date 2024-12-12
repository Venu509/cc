<?php

namespace App\Console\Commands;

use Exception;
use FilesystemIterator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Local\LocalFilesystemAdapter;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Initialize extends Command
{
    protected $signature = 'app:init {--force : Do not ask for user confirmation }';

    protected $description = 'Run & Install dummy data for the Cultural Web Application';

    public function handle(): void
    {
        try {
            DB::connection()->getPdo();
            Log::debug('Database connection successful.');
        } catch (\Exception $e) {
            Log::debug('Database connection failed: '.$e->getMessage());
        }

        if ($this->option('force')) {
            $this->proceed();
        } elseif ($this->confirm('This command will delete your all current inserted data and, Install dummy data. Are you Sure!')) {
            $this->proceed();
        }
    }

    protected function proceed(): void
    {
//        $this->dumpAutoload();

        $this->call('optimize:clear');
        $this->call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        $this->copy();

        $storage = $this->callSilent('storage:link');

        if ($storage) {
            $this->info('Storage Folder Created Successfully');
        } else {
            $this->info('Storage Folder is already exist!');
        }
//        $this->createFolders(); error

        updateFolderPermissions(storage_path());

        $this->info('Required all data successfully installed!');
    }

    private function dumpAutoload(): void
    {
        shell_exec('composer dump-autoload');
    }

    protected function copy(): void
    {
        $storageDisk = env('DEFAULT_STORAGE', 'public');
        $disk = Storage::disk($storageDisk);

        $dummyRootPath = public_path('dummy');
        $publicRootPath = storage_path('app/public');

        if ($storageDisk === 's3') {
            $this->deleteFromS3('/');
            $publicRootPath = '/';
        } else {
            File::deleteDirectory($publicRootPath);
        }

        $this->recursiveCopy($dummyRootPath, $publicRootPath, $disk);
    }

    protected function recursiveCopy($sourceDirectory, $targetDirectory, $disk): void
    {
        $storageDisk = env('DEFAULT_STORAGE', 'public');
        $isS3 = $storageDisk === 's3';

        if ($isS3) {
            $this->recursiveCopyS3($sourceDirectory, $targetDirectory, $disk);
        } else {
            File::ensureDirectoryExists($targetDirectory);

            $directories = File::directories($sourceDirectory);
            foreach ($directories as $directory) {
                $dirName = basename($directory);
                $newTargetDir = $targetDirectory . DIRECTORY_SEPARATOR . $dirName;
                File::ensureDirectoryExists($newTargetDir);
                $this->createSizeFolders($newTargetDir);

                $this->recursiveCopy($directory, $newTargetDir, $disk);
            }

            $files = File::files($sourceDirectory);
            foreach ($files as $file) {
                $this->copyToSizeFolders($file, $targetDirectory, $disk);
            }
        }
    }

    protected function createSizeFolders($targetDirectory): void
    {
        $sizeFolders = folderTypes();

        foreach ($sizeFolders as $size) {
            $sizeFolderPath = $targetDirectory . DIRECTORY_SEPARATOR . $size;
            if (!File::exists($sizeFolderPath)) {
                File::makeDirectory($sizeFolderPath, 0755, true);
            }
        }
    }

    protected function createSizeFoldersS3($targetDirectory, $disk): void
    {
        $sizeFolders = folderTypes();

        foreach ($sizeFolders as $size) {
            $sizeFolderPath = $targetDirectory . '/' . $size;

            try {
                $disk->put($sizeFolderPath . '/.keep', '');
            } catch (Exception $e) {
                Log::error("Failed to create size folder in S3: $sizeFolderPath. Error: " . $e->getMessage());
            }
        }
    }

    protected function recursiveCopyS3($sourceDirectory, $targetDirectory, $disk): void
    {
        $this->createSizeFoldersS3($targetDirectory, $disk);

        $files = File::files($sourceDirectory);
        foreach ($files as $file) {
            $this->copyToSizeFoldersS3($file, $targetDirectory, $disk);
        }

        $directories = File::directories($sourceDirectory);
        foreach ($directories as $directory) {
            $dirName = basename($directory);
            $newTargetDir = $targetDirectory . '/' . $dirName;
            $this->recursiveCopyS3($directory, $newTargetDir, $disk);
        }
    }

    protected function copyToSizeFolders($file, $targetDirectory, $disk): void
    {
        $sizeFolders = folderTypes();

        foreach ($sizeFolders as $size) {
            $sizeFolderPath = $targetDirectory . DIRECTORY_SEPARATOR . $size;
            $targetPath = $sizeFolderPath . DIRECTORY_SEPARATOR . $file->getFilename();

            if ($disk->getAdapter() instanceof LocalFilesystemAdapter) {
                File::copy($file->getRealPath(), $targetPath);
            } else {
                $disk->put($targetPath, file_get_contents($file->getRealPath()));
            }
        }
    }

    protected function copyToSizeFoldersS3($file, $targetDirectory, $disk): void
    {
        $sizeFolders = folderTypes();

        foreach ($sizeFolders as $size) {
            $sizeFolderPath = $targetDirectory . '/' . $size;
            $targetPath = $sizeFolderPath . '/' . $file->getFilename();

            try {
                $disk->put($targetPath, file_get_contents($file->getRealPath()));
                Log::info("Uploaded file to S3: $targetPath");
            } catch (Exception $e) {
                Log::error("Failed to upload file to S3: $targetPath. Error: " . $e->getMessage());
            }
        }
    }

    protected function deleteFromS3($path): void
    {
        $disk = Storage::disk('s3');
        $files = $disk->allFiles($path);

        foreach ($files as $file) {
            $disk->delete($file);
        }
    }

    protected function createFolders(): void
    {
        $folders = [
            'orders',
            'crews',
            'casts',
            'films',
        ];

        $disk = Storage::disk(env('DEFAULT_STORAGE', 'public'));

        collect($folders)->each(function ($folder) use ($disk) {
            $sizes = folderTypes();

            $sizes->each(function ($size) use ($folder, $disk) {
                $disk->makeDirectory($folder . '/' . $size);
            });
        });
    }
}
