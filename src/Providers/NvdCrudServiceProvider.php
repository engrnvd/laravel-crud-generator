<?php

namespace Nvd\Crud\Providers;

use Illuminate\Support\ServiceProvider;
use Nvd\Crud\Commands\Crud;

class NvdCrudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([Crud::class]);
        //$this->loadViewsFrom(__DIR__.'/../templates', 'nvd');
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('crud.php'),
            __DIR__.'/../templates' => base_path('resources/views/vendor/crud/templates'),
            __DIR__.'/../crud-common-templates' => base_path('resources/views/vendor/crud/common'),
        ],'nvd');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
