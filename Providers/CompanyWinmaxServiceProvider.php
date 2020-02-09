<?php

namespace Modules\CompanyWinmax\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class CompanyWinmaxServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('CompanyWinmax', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('CompanyWinmax', 'Config/config.php') => config_path('companywinmax.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('CompanyWinmax', 'Config/config.php'), 'companywinmax'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/companywinmax');

        $sourcePath = module_path('CompanyWinmax', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/companywinmax';
        }, \Config::get('view.paths')), [$sourcePath]), 'companywinmax');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/companywinmax');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'companywinmax');
        } else {
            $this->loadTranslationsFrom(module_path('CompanyWinmax', 'Resources/lang'), 'companywinmax');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('CompanyWinmax', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
