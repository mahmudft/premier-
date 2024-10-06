<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LeagueController;

// Route to generate fixtures
Route::get('/generate-fixtures', [LeagueController::class, 'generateFixtures']);

// Route to simulate matches for a given week
Route::get('/simulate-week/{week}', [LeagueController::class, 'simulateWeek']);

// Route to view current league table
Route::get('/league-table/{week}', [LeagueController::class, 'showLeagueTable']);

Route::get('/', function () {
    return view('welcome');
});
