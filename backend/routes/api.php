<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Man of the Match Live
|--------------------------------------------------------------------------
|
| GET  /api/players              → PlayerController@index  (list all players)
| POST /api/players/{player}/vote → PlayerController@vote  (cast a vote)
|
*/

Route::get('/players', [PlayerController::class, 'index']);
Route::post('/players/{player}/vote', [PlayerController::class, 'vote']);
