<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Old URLs (previous WordPress/Craft sites) are redirected through the CMS "Redirects" module.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            if (! in_array($request->method(), ['GET', 'HEAD'])) {
                return null;
            }
            try {
                $redirect = \App\Models\Redirect::query()->published()
                    ->where('from_path', \App\Models\Redirect::normalizePath($request->getPathInfo()))
                    ->first();
            } catch (\Throwable) {
                return null;
            }

            return $redirect ? redirect($redirect->to_path, $redirect->status_code ?: 301) : null;
        });
    })->create();
