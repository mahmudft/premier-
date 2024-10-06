<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LeagueController;


Route::get('/simulate-week/{week}', [LeagueController::class, 'simulateMatchByWeek']);


Route::get('/league-table/{week}', [LeagueController::class, 'showLeagueTable']);


Route::get('/simulate-championship/{week}', [LeagueController::class, 'simulateChampionshipByWeek']);

Route::get('/', function () {
    return view('welcome');
});
