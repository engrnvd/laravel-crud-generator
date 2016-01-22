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
