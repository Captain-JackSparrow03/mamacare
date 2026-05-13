<?php
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\DashboardController;
use App\Admin\Controllers\ContentController;

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('contenus')->as('contents.')->group(function () {
            Route::get('/',               [ContentController::class, 'index'])->name('index');
            Route::post('/',              [ContentController::class, 'store'])->name('store');
            Route::put('/{content}',      [ContentController::class, 'update'])->name('update');
            Route::delete('/{content}',   [ContentController::class, 'destroy'])->name('destroy');
        });

    });