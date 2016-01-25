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
        $this->loadViewsFrom(__DIR__.'/../templates', 'nvd');
        $this->publishes([
            __DIR__.'/../templates' => base_path('resources/views/crud-templates'),
        ]);
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
