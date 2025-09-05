<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function ($request) {
            if($request->is('admin*')){
                return route('admin.login');
            }elseif($request->is('user*')){
                return route('user.login');
            }else{
                return url('/');
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);
      
return $app;