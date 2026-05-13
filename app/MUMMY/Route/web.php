<?php
use Illuminate\Support\Facades\Route;
use App\MUMMY\Controllers\DashboardController;
use App\MUMMY\Controllers\ProfileController;
use App\MUMMY\Controllers\ReminderController;
use App\MUMMY\Controllers\NoteController;
use App\MUMMY\Controllers\ContentController;
use App\MUMMY\Controllers\BabyController;


Route::middleware(['auth', 'profile.completed'])->group(function () {
    Route::prefix('dashboard')->as('mummy.')->group(function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    });
    
    Route::prefix('rappels')->as('mummy.reminders.')->group(function () {
        Route::get('/', [ReminderController::class, 'index'])->name('index');
        Route::post('/', [ReminderController::class, 'store'])->name('store');
        Route::put('/{reminder}', [ReminderController::class, 'update'])->name('update');
        Route::patch('/{reminder}/toggle', [ReminderController::class, 'toggle'])->name('toggle');
        Route::delete('/{reminder}', [ReminderController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('notes')->as('mummy.notes.')->group(function () {
        Route::get('/',              [NoteController::class, 'index'])->name('index');
        Route::post('/',             [NoteController::class, 'store'])->name('store');
        Route::put('/{note}',        [NoteController::class, 'update'])->name('update');
        Route::delete('/{note}',     [NoteController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('contenus')->as('mummy.contents.')->group(function () {
        Route::get('/',             [ContentController::class, 'index'])->name('index');
        Route::get('/semaine/{week}', [ContentController::class, 'byWeek'])->name('week');
    });

    Route::prefix('bebe')->as('mummy.baby.')->group(function () {
        Route::get('/',              [BabyController::class, 'index'])->name('index');
        Route::get('/semaine/{week}', [BabyController::class, 'byWeek'])->name('week');
    });
});

// Le profil n'a PAS profile.completed (sinon boucle infinie)
Route::middleware('auth')->group(function () {
    Route::prefix('profile')->as('mummy.')->group(function () {
        Route::get('/', [ProfileController::class, 'showCompleteProfile'])->name('profile');
        Route::post('/', [ProfileController::class, 'completeProfile'])->name('profile.add');
    });
});
