<?php

namespace Domain\Test\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Folder names with their permissions',
            'list' => $this->getFolderWisePermissions()
        ]);
    }

    public function store(): JsonResponse
    {
        $publicPath = storage_path('app/public');

        $this->recursiveChmod($publicPath, 0777);

        return response()->json([
            'status' => true,
            'message' => 'Folder names with their permissions',
            'list' => $this->getFolderWisePermissions()
        ]);
    }

    private function recursiveChmod($path, $mode): void
    {
        chmod($path, $mode);

        $items = scandir($path);

        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..') {
                $itemPath = $path . '/' . $item;

                if (is_dir($itemPath)) {
                    $this->recursiveChmod($itemPath, $mode);
                } else {
                    chmod($itemPath, $mode);
                }
            }
        }
    }

    protected function getFolderWisePermissions(): Collection
    {
        $publicPath = 'public';

        $folders = $this->getFoldersWithPermissions($publicPath);

        return collect($folders);
    }

    private function getFoldersWithPermissions($path): array
    {
        $folders = [];

        $contents = Storage::disk('local')->listContents($path);

        // Loop through each item
        foreach ($contents as $item) {
            $relativePath = $item['path'];

            if ($item['type'] === 'dir') {
                $permissions = substr(sprintf('%o', fileperms(storage_path("app/$relativePath"))), -4);

                $folders[] = [
                    'name' => $relativePath,
                    'permissions' => $permissions
                ];

                $nestedFolders = $this->getFoldersWithPermissions($relativePath);

                $folders = array_merge($folders, $nestedFolders);
            }
        }

        return $folders;
    }

    public function setDefaultPassword(): JsonResponse
    {
        User::query()->whereIn('username', [
            'careerconnect@master.com',
            'careerconnect@admin.com',
            'careerconnect@government.com',
            'careerconnect@marketing.com',
            'careerconnect@institution.com',
            'careerconnect@candidate.com',
            'careerconnect@company.com',
        ])->update([
            'password' => Hash::make('12')
        ]);

        return response()->json([
            'status' => true,
            'message' => "Default password (12) updated",
            'updated-users' => [
                'careerconnect@master.com',
                'careerconnect@admin.com',
                'careerconnect@government.com',
                'careerconnect@marketing.com',
                'careerconnect@institution.com',
                'careerconnect@candidate.com',
                'careerconnect@company.com',
            ]
        ]);
    }

}
