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

Route::post('login', [UserController::class, 'login'])->name('users.login');
Route::post('players', [UserController::class, 'register'])->name('users.register');

Route::middleware('auth:api')->group(function () {
    //users
    Route::get('/players', [UserController::class, 'listPlayersRate'])->name('players.listPlayersRate')->middleware('role:admin');
    Route::put('players/{id}', [UserController::class, 'update'])->name('players.update')->middleware('role:player');
    Route::post('logout', [UserController::class, 'logout'])->name('players.logout')->middleware('role:admin,player');
    //games
    Route::post('players/{id}/games', [GameController::class, 'throwingDices'])->name('players.throwingDices')->middleware('role:player');
    Route::get('players/{id}/games', [GameController::class, 'listThrowedGames'])->name('players.listThrowedGames')->middleware('can:players.listThrowedGames');
    Route::delete('/players/{id}/games', [GameController::class, 'deleteAllThrowsOfAPlayer'])->name('players.deleteAllThrowsOfAPlayer')->middleware('role:player');
    //rankings
    Route::get('/players/ranking', [GameController::class, 'indexRanking'])->name('players.indexRanking')->middleware('role:admin');
    Route::get('/players/ranking/winner', [GameController::class, 'winnerRanking'])->name('players.winnerRanking')->middleware('role:admin');    
    Route::get('/players/ranking/loser', [GameController::class, 'loserRanking'])->name('players.loserRanking')->middleware('role:admin');

});
