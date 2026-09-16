<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ListMemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('lists.index');
    }

    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function (): void {
    // Lists (Dev 2) — /lists/joined harus di atas /lists/{list}
    Route::get('/lists', [ListController::class, 'index'])->name('lists.index');
    Route::post('/lists', [ListController::class, 'store'])->name('lists.store');
    Route::get('/lists/joined', [ListMemberController::class, 'index'])->name('lists.joined');
    Route::get('/lists/{list}', [ListController::class, 'show'])->whereNumber('list')->name('lists.show');
    Route::patch('/lists/{list}', [ListController::class, 'update'])->whereNumber('list')->name('lists.update');
    Route::delete('/lists/{list}', [ListController::class, 'destroy'])->whereNumber('list')->name('lists.destroy');

    // Members (Dev 4)
    Route::get('/lists/{list}/members', [ListMemberController::class, 'show'])->whereNumber('list')->name('lists.members.index');
    Route::post('/lists/{list}/members', [ListMemberController::class, 'store'])->whereNumber('list')->name('lists.members.store');
    Route::delete('/lists/{list}/members/{member}', [ListMemberController::class, 'destroy'])->whereNumber(['list', 'member'])->name('lists.members.destroy');

    // Tasks (Dev 3)
    Route::post('/lists/{list}/tasks', [TaskController::class, 'store'])
        ->whereNumber('list')
        ->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])
        ->whereNumber('task')
        ->name('tasks.update');
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])
        ->whereNumber('task')
        ->name('tasks.toggle');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->whereNumber('task')
        ->name('tasks.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
});
