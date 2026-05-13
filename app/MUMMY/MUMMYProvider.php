<?php

namespace App\MUMMY;

use Illuminate\Support\ServiceProvider;

class MUMMYProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/Views','MUMMY');
    }
}
