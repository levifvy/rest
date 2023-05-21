<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GameController;

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

Route::post('login', [UserController::class, 'login']);
Route::post('players', [UserController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    //users
    Route::get('/players', [UserController::class, 'listPlayersRate']);
    Route::put('players/{id}', [UserController::class, 'update']);
    Route::post('logout', [UserController::class, 'logout']);
    //games
    Route::post('players/{id}/games', [GameController::class, 'throwingDices']);
    Route::get('players/{id}/games', [GameController::class, 'listThrowedGames']);
    Route::delete('/players/{id}/games', [GameController::class, 'deleteAllThrowsOfAPlayer']);
    //rankings
    Route::get('/players/ranking', [GameController::class, 'indexRanking']);
    Route::get('/players/ranking/winner', [GameController::class, 'winnerRanking']);    
    Route::get('/players/ranking/loser', [GameController::class, 'loserRanking']);

});
