<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ListMemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function (): void {
    // Routes from Dev 3 (Tasks)
    Route::post('/lists/{list}/tasks', [TaskController::class, 'store'])
        ->whereNumber('list')
        ->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])
        ->name('tasks.update');
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])
        ->name('tasks.toggle');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->name('tasks.destroy');

    // Routes from Dev 2 (Lists)
    Route::get('/lists', [ListController::class, 'index']);
    Route::post('/lists', [ListController::class, 'store']);

    // Routes from Dev 4 (Members)
    Route::get('/lists/joined', [ListMemberController::class, 'index']);
    Route::post('/lists/{todo_list}/members', [ListMemberController::class, 'store']);
    Route::delete('/lists/{todo_list}/members/{member}', [ListMemberController::class, 'destroy']);

    Route::get('/lists/{list}', [ListController::class, 'show']);
    Route::patch('/lists/{list}', [ListController::class, 'update']);
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/admin', fn () => 'Admin area')
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');
