<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\NotFoundHttpException;

class GameController extends Controller
{
    private function creatingDataGame($data, $user)
    {
        $game = $user->games()->create($data);
        $user->rate = $user->putRateMark();
        $user->save();

        return $game; 
    }
    public function listThrowedGames($id)
    {
        return response()->json($this->getUserById($id)->games, 200);
    }

    private function playGame()
    {
        $dice1 = rand(1, 6);
        $dice2 = rand(1, 6);

        return [
            'dice_1' => $dice1,
            'dice_2' => $dice2,
            'win' => $dice1 + $dice2 === 7,
        ];
    }
   
    public function throwingDices($id)
    {
        $game = $this->creatingDataGame($this->playGame(), $this->getUserById($id));

        return response()->json(['win' => $game->win,'dice_1' => $game->dice_1,'dice_2' => $game->dice_2], 200);
    }

    private function getUserById($id)
{
    $user = User::find($id);
    
    if (!$user) {
        return response()->json(['error' => 'This user was not found'], 404);
    }
    
    if (Auth::user()->id != $user->id) {
        return response()->json(['error' => 'This user is Unauthorized'], 401);
    }
    
    return $user;
}

    public function deleteAllThrowsOfAPlayer($id)
    {
        $this->getUserById($id)->games()->delete();
        
        return response()->json(['message' => 'Games have been deleted'], 200);
    }
    
}