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
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserRole::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // التطبيق مربوط بـ 127.0.0.1 فقط والوصول الوحيد له عبر Caddy على
        // نفس السيرفر، فالوثوق بكل الوسطاء هون آمن وضروري لقراءة X-Forwarded-Proto
        // بشكل صحيح (حتى لا يعتقد لارافيل أن الطلبات عبر HTTP دائمًا).
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
