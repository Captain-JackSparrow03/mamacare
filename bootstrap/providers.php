<?php

use App\Providers\AppServiceProvider;

return [
    App\Admin\AdminProvider::class,
    App\MUMMY\MUMMYProvider::class,
    App\auth\AuthProvider::class,
    AppServiceProvider::class,
];
