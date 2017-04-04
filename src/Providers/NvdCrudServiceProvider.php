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
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('crud.php'),
            __DIR__.'/../classic-templates' => base_path('resources/views/vendor/crud/classic-templates'),
            __DIR__.'/../single-page-templates' => base_path('resources/views/vendor/crud/single-page-templates'),
            __DIR__.'/../admin-lte-templates' => base_path('resources/views/vendor/crud/admin-lte-templates'),
            __DIR__.'/../admin-lte-templates/dist' => public_path('dist'),
            __DIR__.'/../admin-lte-templates/plugins' => public_path('plugins'),
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
