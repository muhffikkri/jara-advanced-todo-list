<?php

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

    // Routes from Dev 4 (Members)
    Route::get('/lists/joined', [\App\Http\Controllers\ListMemberController::class, 'index']);
    Route::post('/lists/{todo_list}/members', [\App\Http\Controllers\ListMemberController::class, 'store']);
    Route::delete('/lists/{todo_list}/members/{member}', [\App\Http\Controllers\ListMemberController::class, 'destroy']);
});
