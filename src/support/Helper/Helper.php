<?php

namespace Support\Helper;

use Domain\Attendance\Models\Attendance;
use Domain\Attendance\Resources\AttendanceResources;
use Exception;
use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Spatie\Permission\Models\Permission;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use function PHPUnit\Framework\isEmpty;

trait Helper
{
    public function imageCheck(
        string  $path,
        ?string $default = 'assets/template/images/not-found.png'
    ): string
    {
        return Storage::disk('public')->exists($path) ? asset('storage/' . $path) : asset($default);
    }

    public function displayPrice(float|int $price): string
    {
        return displayPrice($price);
    }

    public function getCurrentRoute(): object|string|null
    {
        return Request::route();
    }

    public function throwable(Exception|Throwable $exception, ?bool $isAPI = false): JsonResponse|RedirectResponse
    {
        $errorMessage = [
            'title' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'previous' => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
        ];

        if($isAPI) {
            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ], 500);
        }

        if ($this->requestTypeCheck()) {
            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ], 500);
        }

        return redirect()->back()->withFlash([
            'type' => 'error',
            'title' => 'Exception Occur',
            'message' => $errorMessage,
        ]);
    }

    public function requestTypeCheck(
        string $type = 'api'
    ): bool
    {
        return in_array($type, request()->route()->getAction('middleware'), true);
    }

    public function months(): array
    {
        $month = [];

        for ($m = 1; $m <= 12; $m++) {
            $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }

        return $month;
    }

    protected function saveFile(
        mixed         $model,
        ?UploadedFile $uploadedLogo = null,
        ?string       $columnName = 'image',
        ?string       $destination = null,
        ?string       $customFileName = null,
        ?array        $sizes = [],
        ?string $storageDisk = null
    ): ?string
    {
        $storageDisk = $storageDisk ?? env('DEFAULT_STORAGE', 'public');

        if (!$uploadedLogo) {
            return $model[$columnName];
        }

        folderTypes()->each(function ($folder) use ($columnName, $model, $destination, $storageDisk) {
            $this->deleteFileIfExists($destination . $folder . '/' . $model[$columnName], $storageDisk);
        });

        $fileName = $this->generateSlug($columnName) . '-' .
            ($customFileName !== null ? $this->generateSlug($customFileName) . '-' : '') . uniqid('', true) . '.' . $uploadedLogo->extension();

        if (!empty($sizes)) {
            foreach ($sizes as $size => $dims) {
                $image = ImageManager::gd()->read($uploadedLogo->getRealPath());

                $image->scaleDown($dims['width'], $dims['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $image->save(storage_path('app/' . $storageDisk . '/' . $destination . $size . '/' . $fileName), 60);
            }
        } else {
            folderTypes()->each(function ($folder) use ($fileName, $uploadedLogo, $destination, $storageDisk) {
                Storage::disk($storageDisk)->putFileAs($destination . $folder, $uploadedLogo, $fileName);
            });
        }

        return $fileName;
    }

    public function deleteFileIfExists(string $path, ?string $storage = 'public'): void
    {
        if (Storage::disk($storage)->exists($path)) {
            Storage::disk($storage)->delete($path);
        }
    }

    public function generateSlug(string $string): string
    {
        return Str::slug($string);
    }

    protected function getIp(): ?string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
    }

    public function convertToCarbonFormat($timestamp): Carbon
    {
        return Carbon::parse($timestamp);
    }

    public function dateFilter(
        Builder $builder,
        string  $type,
        array   $date,
        string  $column = 'date'
    ): Builder
    {
        $from = Carbon::parse($date['from'])->format('Y-m-d');
        $fromCarbonFormat = Carbon::parse($from);

        return $builder->when($type === 'single', function (Builder $builder) use ($column, $from) {
            return $builder->whereDate($column, $from);
        })
            ->when($type === 'range', function (Builder $builder) use ($column, $date, $from) {
                $to = Carbon::parse($date['to'])->format('Y-m-d');

                return $builder->whereBetween($column, [$from, $to]);
            })
            ->when($type === 'thisWeek', function (Builder $builder) use ($column, $fromCarbonFormat) {
                return $builder->whereBetween($column, [$fromCarbonFormat->startOfWeek(), $fromCarbonFormat->endOfWeek()]);
            })
            ->when($type === 'thisMonth', function (Builder $builder) use ($column, $fromCarbonFormat) {
                return $builder->whereBetween($column, [$fromCarbonFormat->startOfMonth(), $fromCarbonFormat->endOfMonth()]);
            })
            ->when($type === 'thisYear', function (Builder $builder) use ($column, $fromCarbonFormat) {
                return $builder->whereBetween($column, [$fromCarbonFormat->startOfYear(), $fromCarbonFormat->endOfYear()]);
            });
    }

    public function validateDateParam($params, $dateType): ?JsonResponse
    {
        if (!in_array($dateType, ['single', 'thisWeek', 'thisMonth', 'thisYear'])) {
            if (!array_key_exists('to', $params['date'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please provide the [to] key in date parameter',
                ]);
            }
        } elseif (!array_key_exists('from', $params['date'])) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide the [from] key in date parameter',
            ]);
        }

        return null;
    }

    public function getAgeFromDOB(string $dob): int
    {
        return now()->diffInYears($dob);
    }


    public function color(?string $type = null): string
    {
        $colors = [
            'company' => 'green',
            'government' => 'yellow',
            'institution' => 'indigo',
            'candidate' => 'purple',
        ];

        return $type ? ($colors[$type] ?? 'green') : 'green';
    }

    public function applicantStatusColor(?string $type = null): string
    {
        $colors = [
            'applied' => 'indigo',
            'viewed' => 'yellow',
            'shortlisted' => 'green',
            'rejected' => 'dark',
        ];

        return $type ? ($colors[$type] ?? 'green') : 'green';
    }

    public function createPermission(
        string $domain,
        string $permission,
        ?string $guardName = 'web'
    ): void {
        $permissionName = slugGenerator($permission . '-' . $domain);

        if (!Permission::where('name', $permissionName)->exists()) {
            Permission::create([
                'name' => $permissionName,
                'domain' => $domain,
                'ability' => $permission,
                'guard_name' => $guardName,
                'display_name' => ucfirst($permission),
            ]);
        }
    }

    public function attendance(): array
    {
        $attendance = [
            'attendance' => null,
            'isClockedIn' => false
        ];

        if(Auth::check() && (Auth::user()->roles()->first()->name === 'marketing')) {
            $attendanceBuilder = Attendance::query()->where('user_id', auth()->user()->id);
            $attendance = [
                'attendance' => $attendanceBuilder->exists() ? new AttendanceResources($attendanceBuilder->latest()->first()) : null,
                'isClockedIn' => $attendanceBuilder->whereNotNull('clock_in_at')->whereNull('clock_out_at')->exists()
            ];
        }

        return $attendance;
    }
}
