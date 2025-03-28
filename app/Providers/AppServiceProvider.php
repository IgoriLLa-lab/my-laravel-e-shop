<?php

namespace App\Providers;

use App\Api\V1\Auth\JWT;
use App\Guards\JWTGuard;
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::hashClientSecrets();

        Passport::tokensCan([
            'user-avatar' => 'Get User avatar',
            'check-status' => 'Check order status',
        ]);

        //TODO разобраться с директивой
        Blade::directive('routeActive', function ($route) {
            return "<?php echo
                Route::currentRouteNamed($route) ? 'class=\"active\"' : ''
                ?>";
        });

        Product::observe(ProductObserver::class);

        $this->app->singleton(JWT::class, function ($app) {
            $secret = config('auth.jwt_secret');
            if (!$secret) {
                throw new \Exception('JWT_SECRET не задан в конфигурации или .env');
            }
            return new JWT($secret);
        });
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            return new JwtGuard(
                $app->make(JWT::class),
                Auth::createUserProvider($config['provider']),
            );
        });

    }
}
