<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                return Inertia::render('ErrorPages/Forbidden', ['status' => 403])
                    ->toResponse($request)
                    ->setStatusCode(403);
            }
        });

        // Handle 404 Not Found (Model Not Found and HTTP 404)
        $this->renderable(function (ModelNotFoundException $e, $request) {
            return Inertia::render('ErrorPages/NotFound', ['status' => 404])
                ->toResponse($request)
                ->setStatusCode(404);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return Inertia::render('ErrorPages/NotFound', ['status' => 404])
                ->toResponse($request)
                ->setStatusCode(404);
        });

        // Handle 500 Internal Server Error
        $this->renderable(function (Throwable $e, $request) {
            if ($this->isHttpException($e) && $e->getStatusCode() === 500) {
                return Inertia::render('ErrorPages/Server', ['status' => 500])
                    ->toResponse($request)
                    ->setStatusCode(500);
            }
        });

        // Handle 502 Bad Gateway Error
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 502) {
                return Inertia::render('ErrorPages/BadGateway', ['status' => 502])
                    ->toResponse($request)
                    ->setStatusCode(502);
            }
        });

        // General Fallback for other HTTP status codes
        $this->renderable(function (HttpException $e, $request) {
            $statusCode = $e->getStatusCode();

            return Inertia::render('ErrorPages/General', ['status' => $statusCode])
                ->toResponse($request)
                ->setStatusCode($statusCode);
        });
    }

    public function render($request, Throwable $e): \Illuminate\Http\Response|JsonResponse|\Inertia\Response|RedirectResponse|Response
    {
        if ($this->isHttpException($e)) {
            $statusCode = $e->getStatusCode();

            return match ($statusCode) {
                403 => Inertia::render('ErrorPages/Forbidden', ['status' => 403])
                    ->toResponse($request)
                    ->setStatusCode(403),
                404 => Inertia::render('ErrorPages/NotFound', ['status' => 404])
                    ->toResponse($request)
                    ->setStatusCode(404),
                500 => Inertia::render('ErrorPages/Server', ['status' => 500])
                    ->toResponse($request)
                    ->setStatusCode(500),
                502 => Inertia::render('ErrorPages/BadGateway', ['status' => 502])
                    ->toResponse($request)
                    ->setStatusCode(502),
                default => Inertia::render('ErrorPages/General', ['status' => $statusCode])
                    ->toResponse($request)
                    ->setStatusCode($statusCode),
            };
        }

        return parent::render($request, $e);
    }
}
