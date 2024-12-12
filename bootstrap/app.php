<?php

$app = (new App\DREAMCAREER(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
))->useAppPath(__DIR__ . '/../src/app');

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
