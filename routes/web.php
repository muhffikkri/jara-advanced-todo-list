<?php

use App\Http\Controllers\ListController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Lists (F2 — Dev 2)
Route::middleware('auth')->group(function () {
    Route::get('/lists', [ListController::class, 'index']);
    Route::post('/lists', [ListController::class, 'store']);
    Route::get('/lists/{list}', [ListController::class, 'show']);
    Route::patch('/lists/{list}', [ListController::class, 'update']);
});
