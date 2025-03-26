<?php

namespace App\Providers;

use App\Guards\JWTGuard;
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Src\Auth\JWT;

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

        $this->app->instance(JWT::class, new JWT(
            config('auth.jwt_secret'),
        ));
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            return new JwtGuard(
                $app[JWT::class],
                Auth::createUserProvider($config['provider']),
            );
        });

    }
}
