<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\RosterFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/parse_file', [RosterFileController::class, 'parse'])->name('parse-file');
Route::get('/activities', [ActivityController::class, 'show'])->name('get-activities');
