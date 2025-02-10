<?php

namespace App\Providers;

use App\Components\BasketLogic;
use App\Models\Product;
use App\Observers\ProductObserver;
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

        //TODO разобраться с дерективой
        Blade::directive('routeActive', function ($route) {
            return "<?php echo
                Route::currentRouteNamed($route) ? 'class=\"active\"' : ''
                ?>";
        });

        Product::observe(ProductObserver::class);

    }
}
