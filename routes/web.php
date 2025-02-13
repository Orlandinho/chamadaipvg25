<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitantController;
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

    Route::controller(StudentController::class)->group(function () {
        Route::get('/estudantes', 'index')->name('students.index');
        Route::get('/estudantes/criar', 'create')->name('students.create');
        Route::post('/estudantes', 'store')->name('students.store');
        Route::get('/estudantes/{student:slug}', 'show')->name('students.show');
        Route::get('/estudantes/{student:slug}/editar', 'edit')->name('students.edit');
        Route::patch('/estudantes/{student}', 'update')->name('students.update');
        Route::delete('/estudantes/{student}', 'destroy')->name('students.destroy');
    });

    Route::controller(VisitantController::class)->group(function () {
        Route::get('/visitantes', 'index')->name('visitants.index');
        Route::get('/visitantes/criar', 'create')->name('visitants.create');
        Route::post('/visitantes', 'store')->name('visitants.store');
        Route::get('/visitantes/{visitant:slug}', 'show')->name('visitants.show');
        Route::get('/visitantes/{visitant:slug}/editar', 'edit')->name('visitants.edit');
        Route::patch('/visitantes/{visitant}', 'update')->name('visitants.update');
        Route::delete('/visitantes/{visitant}', 'destroy')->name('visitants.destroy');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/chamada', 'index')->name('registers.index');
        Route::patch('/chamada/{student}/{sunday}', 'update')->name('registers.update');
    });

});

require __DIR__.'/auth.php';
