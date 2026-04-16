<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $this->app['router']->aliasMiddleware('jwt.auth', \App\Http\Middleware\JWTMiddleware::class);
        $this->app['router']->aliasMiddleware('isSuperAdmin', \App\Http\Middleware\isSuperAdmin::class);
        Route::middleware([
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ])->group([

        ]);

        Route::middlewareGroup('api', [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // \App\Http\Middleware\JwtMiddleware::class, // optional
        ]);
    }
}
