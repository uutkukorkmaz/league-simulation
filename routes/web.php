<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\FixtureController;

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

Route::get('/', LeagueController::class)->name('standings');

Route::group(['prefix' => 'fixture', 'as' => 'fixture.'], function () {
    Route::get('/', [FixtureController::class, 'index'])->name('index');
    Route::delete('reset', [FixtureController::class, 'reset'])->name('reset');
    Route::post('generate', [FixtureController::class, 'generate'])->name('generate');
});
Route::post('simulation', \App\Http\Controllers\SimulationController::class)->name('simulation');
