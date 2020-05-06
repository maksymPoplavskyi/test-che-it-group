<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\LoggingController;
use Illuminate\Support\Facades\Route;

    Route::get('/', [LoggingController::class, 'index']);
    Route::post('/create', [LoggingController::class, 'create']);
    Route::patch('/update/{id}', [LoggingController::class, 'update']);
    Route::delete('/delete/{id}',[LoggingController::class, 'delete']);
