<?php

use App\Http\Middleware\CORSMiddleware;
use App\Http\Middleware\ForceJson;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\JWTMiddlewareOptional;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt' => JWTMiddleware::class,
            'jwt_optional' => JWTMiddlewareOptional::class,
            'cors' => CORSMiddleware::class,
        ]);
        $middleware->appendToGroup('api', ForceJson::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 401);
            }
        });
    })->create();
