<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('careers')->group(function () {
    Route::get('/', [App\Http\Controllers\CareerController::class, 'list']);
    Route::get('/{id}', [App\Http\Controllers\CareerController::class, 'show']);
    Route::post('/create', [App\Http\Controllers\CareerController::class, 'store']);
    Route::put('/{id}', [App\Http\Controllers\CareerController::class, 'update']);
    Route::delete('delete/{id}', [App\Http\Controllers\CareerController::class, 'destroy']);
});
Route::prefix('employees')->group(function () {
    Route::get('/', [App\Http\Controllers\EmployeeController::class, 'list']);
    Route::get('/{id}', [App\Http\Controllers\EmployeeController::class, 'show']);
    Route::post('/', [App\Http\Controllers\EmployeeController::class, 'store']);
    Route::put('/{id}', [App\Http\Controllers\EmployeeController::class, 'update']);
    Route::delete('delete/{id}', [App\Http\Controllers\EmployeeController::class, 'destroy']);
});
