<?php

use Domain\API\Authentication\Actions\RefreshTokenController;
use Domain\API\Authentication\Controllers\AuthController;
use Domain\API\Authentication\Controllers\PasswordResetController;
use Domain\Attendance\Controllers\API\AttendanceController;
use Domain\Branch\Controllers\API\BranchController;
use Domain\Candidate\Controllers\API\CandidateController;
use Domain\Category\Controllers\API\CategoryController;
use Domain\College\Controllers\API\CollegeController;
use Domain\Company\Controllers\API\CompanyController;
use Domain\Country\Controllers\API\CountryController;
use Domain\Dashboard\Controllers\API\DashboardController;
use Domain\Dashboard\Controllers\API\StarterController;
use Domain\DeviceToken\Controllers\API\DeviceTokenController;
use Domain\Filter\Controllers\API\FilterController;
use Domain\Job\Controllers\API\JobController;
use Domain\Lead\Controllers\API\LeadController;
use Domain\Notification\Controllers\API\NotificationController;
use Domain\Project\Controllers\API\ProjectController;
use Domain\ProjectName\Controllers\API\ProjectNameController;
use Domain\Resume\Controllers\API\ResumeController;
use Domain\Role\Controllers\API\RoleController;
use Domain\SavedVacancy\Controllers\API\SavedVacancyController;
use Domain\Skill\Controllers\API\SkillController;
use Domain\Student\Controllers\API\StudentController;
use Domain\Test\Controllers\SettingsController;
use Domain\Test\Controllers\TestController;
use Domain\User\Controllers\API\UserController;
use Domain\Workshop\Controllers\API\WorkshopController;
use Domain\WorkshopName\Controllers\API\WorkshopNameController;
use Illuminate\Support\Facades\Route;


Route::get('starter', [StarterController::class, 'index'])->name('starter');
Route::get('migrate-user', [AuthController::class, 'migrate'])->name('auth.migrate');
Route::post('check', [AuthController::class, 'check'])->name('auth.check');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('user/restore', [AuthController::class, 'restore'])->name('auth.restore');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword'])->name('auth.forgot-password');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('reset-password');
Route::post('account-verification', [AuthController::class, 'accountVerification'])->name('auth.account-verification');

Route::middleware(['validate-api-key'])
    ->group(function () {

        Route::middleware([
            'auth:sanctum',
            'unauthorized',
        ])->group(function () {
            Route::middleware([
                'check-active-api-users'
            ])->group(function () {
                Route::prefix('/auth')
                    ->name('auth.')
                    ->group(function () {
                        Route::get('/refresh-token', RefreshTokenController::class)->name('refresh-token');
                        Route::post('/change-password', [PasswordResetController::class, 'changePassword'])->name('auth.change-password');

                        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
                        Route::get('/profile/status', [UserController::class, 'status'])->name('profile.status');
                        Route::post('/profile/update', [UserController::class, 'update'])->name('auth.profile.update');
                        Route::post('/profile/avatar/upload', [UserController::class, 'upload'])->name('profile.avatar.upload');
                        Route::post('/profile/avatar/destroy', [UserController::class, 'destroy'])->name('profile.avatar.destroy');
                        Route::post('/profile/delete', [UserController::class, 'delete'])->name('profile.delete');
                    });

                Route::post('device-tokens/store', [DeviceTokenController::class, 'store'])->name('device-tokens.store');

                Route::get('dashboard', [DashboardController::class, 'index'])->name('index');

                Route::prefix('notifications')
                    ->name('notifications.')
                    ->controller(NotificationController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/show', 'show')->name('show');
                        Route::get('/unseen', 'unseen')->name('unseen');
                        Route::post('/read', 'read')->name('read');
                    });

                Route::prefix('roles')
                    ->name('roles.')
                    ->controller(RoleController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/show', 'show')->name('show');
                    });

                Route::prefix('jobs')
                    ->name('jobs.')
                    ->controller(JobController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/apply', 'apply')->name('apply');
                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/assign', 'assign')->name('assign');
                        Route::post('/status', 'status')->name('status');
                        Route::post('/destroy', 'destroy')->name('destroy');
                    });

                Route::prefix('saved-jobs')
                    ->name('saved-jobs.')
                    ->controller(SavedVacancyController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/store', 'store')->name('store');
                    });

                Route::prefix('resumes')
                    ->name('resumes.')
                    ->controller(ResumeController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/{user}', 'show')->name('show');
                    });

                Route::prefix('branches')
                    ->name('branches.')
                    ->controller(BranchController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/store', 'store')->name('store');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::post('/destroy', 'destroy')->name('destroy');
                    });

                Route::prefix('students')
                    ->name('students.')
                    ->controller(StudentController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/store', 'store')->name('store');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::post('/status', 'status')->name('status');
                        Route::post('/bulk', 'bulk')->name('bulk');
                        Route::get('/download', 'download')->name('download');
                        Route::post('/delete', 'delete')->name('delete');
                        Route::post('/destroy', 'destroy')->name('destroy');
                    });

                Route::prefix('workshops')
                    ->name('workshops.')
                    ->controller(WorkshopController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });

                Route::prefix('workshops-names')
                    ->name('workshops-names.')
                    ->controller(WorkshopNameController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                    });

                Route::prefix('projects')
                    ->name('projects.')
                    ->controller(ProjectController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });

                Route::prefix('projects-names')
                    ->name('projects-names.')
                    ->controller(ProjectNameController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                    });

                Route::prefix('attendances')
                    ->name('attendances.')
                    ->controller(AttendanceController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/store', 'store')->name('store');
                    });

                Route::prefix('leads')
                    ->name('leads.')
                    ->controller(LeadController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });

                Route::prefix('colleges')
                    ->name('colleges.')
                    ->controller(CollegeController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });

                Route::prefix('companies')
                    ->name('companies.')
                    ->controller(CompanyController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });

                Route::prefix('candidates')
                    ->name('candidates.')
                    ->controller(CandidateController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/show', 'show')->name('show');
                        Route::post('/store', 'store')->name('store');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

            Route::prefix('countries')
                ->name('countries.')
                ->controller(CountryController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                });

            Route::prefix('skills')
                ->name('skills.')
                ->controller(SkillController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                });

            Route::prefix('vacancy-categories')
                ->name('vacancy-categories.')
                ->controller(CategoryController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                });

            Route::prefix('filters')
                ->name('filters.')
                ->controller(FilterController::class)
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                });
        });

        Route::prefix('tests')
            ->name('tests.')
            ->controller(TestController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/list-folders-with-permissions', 'list')->name('list');
            });

        Route::prefix('project-settings')
            ->name('project-settings.')
            ->controller(SettingsController::class)
            ->group(function () {
                Route::get('/list-folders-with-permissions', 'index')->name('index');
                Route::get('/change-folder-permissions', 'store')->name('store');
                Route::get('/set-default-password', 'setDefaultPassword')->name('set-default-password');
            });
    });