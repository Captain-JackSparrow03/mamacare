<?php

use App\Providers\AppServiceProvider;

return [
    App\Admin\AdminProvider::class,
    App\MUMMY\MUMMYProvider::class,
    App\Auth\AuthProvider::class,
    AppServiceProvider::class,
];
