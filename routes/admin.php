<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Domain\API\Authentication\Controllers\AuthController;
use Domain\Application\Controllers\ApplicationController;
use Domain\Attendance\Controllers\AttendanceController;
use Domain\Auth\Controllers\LoginController;
use Domain\Banner\Controllers\BannerController;
use Domain\Branch\Controllers\BranchController;
use Domain\Candidate\Controllers\CandidateController;
use Domain\Category\Controllers\CategoryController;
use Domain\College\Controllers\CollegeController;
use Domain\Company\Controllers\CompanyController;
use Domain\Country\Controllers\CountryController;
use Domain\Dashboard\Controllers\DashboardController;
use Domain\Job\Controllers\JobController;
use Domain\Lead\Controllers\LeadController;
use Domain\MyAccount\Controllers\MyAccountController;
use Domain\Project\Controllers\ProjectController;
use Domain\ProjectName\Controllers\ProjectNameController;
use Domain\Resume\Controllers\ResumeController;
use Domain\Role\Controllers\RoleController;
use Domain\SavedVacancy\Controllers\SavedVacancyController;
use Domain\Student\Controllers\StudentController;
use Domain\User\Controllers\UserController;
use Domain\Vacancy\Controllers\AppliedCandidateController;
use Domain\Vacancy\Controllers\VacancyController;
use Domain\Workshop\Controllers\WorkshopController;
use Domain\WorkshopName\Controllers\WorkshopNameController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['role-based-logout'])->get('/check-session', function () {
    return response()->json(['authenticated' => Auth::check()]);
})->name('admin.check-session');

Route::get('/unauthorized', static function () {
    return Inertia::render('ErrorPages/Unauthorized');
})->name('unauthorized');

Route::prefix('countries')
    ->name('admin.countries.')
    ->controller(CountryController::class)
    ->group(function () {
        Route::get('/fetch', 'fetch')->name('fetch');
    });

Route::get('/admin', static function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => false,
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware('guest')->name('admin.login');


Route::get('/marketing', static function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => false,
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware('guest')->name('marketing.login');

Route::middleware(['role-based-logout'])
    ->prefix('admin')
    ->controller(ForgotPasswordController::class)
    ->group(function () {
        Route::post('password/email', 'sendResetLinkEmail')->name('admin.password.email');
        Route::get('password/email/rules', 'rules')->name('admin.password.email.rules');
    });

Route::middleware(['role-based-logout'])
    ->prefix('admin')
    ->controller(LoginController::class)
    ->group(function () {
        Route::get('rules', 'rules')->name('admin.login.rules');
        Route::get('login', 'showLoginForm')->name('admin.login')->middleware('guest');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->name('admin.logout');
    });

Route::controller(RegisterController::class)->group(function () {
    Route::get('register/rules', 'rules')->name('register.rules');
    Route::get('register', 'showRegistrationForm')->name('register');
    Route::post('register', 'register');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('send-otp', 'check')->name('auth.sendOtp');
    Route::post('verify-otp', 'verifyOTP')->name('auth.verifyOtp');
});

Route::middleware([
    'auth:sanctum',
//    config('jetstream.auth_session'),
    'verified',
    'role-based-logout'
])->name('admin.')->prefix('admin')->group(function () {

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('my-accounts')
        ->name('my-accounts.')
        ->controller(MyAccountController::class)
        ->group(function () {
            Route::get('/rules', 'rules')->name('rules');
            Route::get('/fetch', 'fetch')->name('fetch');
            Route::get('/{user}', 'show')->name('show');
            Route::post('/{user}', 'update')->name('update');
            Route::post('/info/{user}', 'info')->name('info');
            Route::post('/remove/{user}', 'remove')->name('remove');
        });

    Route::prefix('auth')
        ->name('auth.')
        ->controller(ChangePasswordController::class)
        ->group(function () {
            Route::get('/change-password-rules', 'rules')->name('change-password-rules');
            Route::get('change-password', 'showChangePasswordForm')->name('change-password-form');
            Route::post('change-password', 'changePassword')->name('change-password');
        });

    Route::middleware([
        'check-domains',
        'check-permissions',
    ])->group(function () {
        Route::prefix('roles')
            ->name('roles.')
            ->controller(RoleController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/fetch', 'fetch')->name('fetch');
                Route::get('/duplicate', 'duplicate')->name('duplicate');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{role}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('users')
            ->name('users.')
            ->controller(UserController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::get('/duplicate', 'duplicate')->name('duplicate');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{user}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::put('/status', 'status')->name('status');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
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
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{lead}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::put('/status', 'status')->name('status');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('banners')
            ->name('banners.')
            ->controller(BannerController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{banner}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::put('/status', 'status')->name('status');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('colleges')
            ->name('colleges.')
            ->controller(CollegeController::class)
            ->group(function () {
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{user}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::put('/status', 'status')->name('status');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('resumes')
            ->name('resumes.')
            ->controller(ResumeController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/fetch', 'fetch')->name('fetch');
                Route::get('/{user}', 'show')->name('show');
            });

        Route::prefix('jobs')
            ->name('jobs.')
            ->controller(JobController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/fetch', 'fetch')->name('fetch');
                Route::get('/{vacancy}', 'show')->name('show');
            });

        Route::prefix('saved-jobs')
            ->name('saved-jobs.')
            ->controller(SavedVacancyController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/fetch', 'fetch')->name('fetch');
            });

        Route::prefix('candidates')
            ->name('candidates.')
            ->controller(CandidateController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/fetch', 'fetch')->name('fetch');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{user}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::put('/status', 'status')->name('status');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('companies')
            ->name('companies.')
            ->controller(CompanyController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/fetch', 'fetch')->name('fetch');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{user}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::put('/status', 'status')->name('status');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('branches')
            ->name('branches.')
            ->controller(BranchController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{branch}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::get('/view', 'view')->name('view');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('students')
            ->name('students.')
            ->controller(StudentController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::post('/bulk', 'bulk')->name('bulk');
                Route::get('/download', 'download')->name('download');
                Route::get('/uploading', 'uploading')->name('uploading');
                Route::post('/delete', 'delete')->name('delete');

                Route::prefix('{student}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('workshops')
            ->name('workshops.')
            ->controller(WorkshopController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{workshop}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('projects-names')
            ->name('projects-names.')
            ->controller(ProjectNameController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{projectName}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('workshops-names')
            ->name('workshops-names.')
            ->controller(WorkshopNameController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{workshopName}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('projects')
            ->name('projects.')
            ->controller(ProjectController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{project}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('categories')
            ->name('categories.')
            ->controller(CategoryController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/fetch', 'fetch')->name('fetch');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{Category}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('vacancies')
            ->name('vacancies.')
            ->controller(VacancyController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/rules', 'rules')->name('rules');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{vacancy}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::post('/update', 'update')->name('update');
                        Route::delete('/destroy', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('applications')
            ->name('applications.')
            ->controller(ApplicationController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');

                Route::prefix('{vacancy}')
                    ->group(function () {
                        Route::get('/show', 'show')->name('show');
                        Route::get('/resume/{user}', 'view')->name('view');
                    });
            });

        Route::prefix('applied-candidates')
            ->name('applied-candidates.')
            ->controller(AppliedCandidateController::class)
            ->group(function () {
                Route::prefix('{vacancy}')
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/status', 'status')->name('status');
                    });
            });

    });
});
