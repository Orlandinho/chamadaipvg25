<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::get('/colaboradores', 'index')->name('users.index');
        Route::get('/colaboradores/criar', 'create')->name('users.create');
        Route::post('/colaboradores', 'store')->name('users.store');
        Route::get('/colaboradores/{user:slug}', 'show')->name('users.show');
        Route::get('/colaboradores/{user:slug}/editar', 'edit')->name('users.edit');
        Route::patch('/colaboradores/{user}', 'update')->name('users.update');
        Route::delete('/colaboradores/{user}', 'destroy')->name('users.destroy');
    });

    Route::controller(ClassroomController::class)->group(function () {
        Route::get('/classes', 'index')->name('classrooms.index');
        Route::get('/classes/criar', 'create')->name('classrooms.create');
        Route::post('/classes', 'store')->name('classrooms.store');
        Route::get('/classes/{classroom:slug}', 'show')->name('classrooms.show');
        Route::get('/classes/{classroom:slug}/editar', 'edit')->name('classrooms.edit');
        Route::patch('/classes/{classroom}', 'update')->name('classrooms.update');
        Route::delete('/classes/{classroom}', 'destroy')->name('classrooms.destroy');
    });

});

require __DIR__.'/auth.php';
