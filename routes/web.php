<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use Domain\Web\Controllers\LandingController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('storage-link', static function () {
    return Artisan::call('storage:link');
});

Route::get('cache-clear', static function () {
    return Artisan::call('optimize:clear');
});



Route::name('front.')
    ->controller(LandingController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/jobs', 'jobList')->name('jobs.index');
        Route::get('/jobs/fetch', 'jobFetch')->name('jobs.fetch');
        Route::get('/jobs/{slug}', 'jobDetail')->name('jobs.show');

        Route::get('/about-us', 'aboutUs')->name('about-us');
        Route::get('/contact-us', 'contactUs')->name('contact-us');


        Route::prefix('auth')
            ->name('auth.')
            ->controller(ResetPasswordController::class)
            ->group(function () {
                Route::post('set-password', 'setPassword')->name('set-password');
            });
    });

//Route::get('/login', static function () {
//    return Inertia::render('Auth/Login', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//})->name('login');
//
//Route::get('/register', static function () {
//    return Inertia::render('Auth/Register', [
//        'canLogin' => Route::has('register'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//})->name('register');