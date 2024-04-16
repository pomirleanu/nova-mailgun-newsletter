<?php

namespace Pomirleanu\MailgunNewsletter;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use Pomirleanu\MailgunNewsletter\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
        $this->provideConfigToScript();

    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        //if ($this->app->routesAreCached()) {
        //    return;
        //}

        Nova::router(['nova', Authenticate::class,
                      //Authorize::class
        ], 'mailgun-newsletter')
            ->group(__DIR__.'/../routes/inertia.php');

        Route::middleware(['nova',
                           Authorize::class
        ])
            ->prefix('nova-vendor/pomirleanu/mailgun-newsletter')
            ->group(__DIR__.'/../routes/api.php');
    }

    protected function provideConfigToScript()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::provideToScript([
                'nova_mailgun_newsletter_tool' => [
                    'polling' => config('mailgun-newsletter-tool.polling'),
                    'polling_interval' => config('mailgun-newsletter-tool.polling_interval'),
                ],
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
