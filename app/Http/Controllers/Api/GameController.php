<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        
        return response()->json(['message' => 'The games have been deleted'], 200);
    }

    //Rankings

    public function indexRanking()
{
    $average = DB::table('users')->avg('rate');
    $roundedAverage = round($average, 2);
    $formattedAverage = number_format($roundedAverage, 2);

    if ($formattedAverage < 0) {
        $formattedAverage = 0;
    } elseif ($formattedAverage > 100) {
        $formattedAverage = 100;
    }

    return response()->json(['average_rate_throws_won' => $formattedAverage.' %'], 200);
}

    public function winnerRanking()
    {
        $user = DB::table('users')->orderByDesc('rate')->first();

        return response()->json([
            'user' => $user->name,
            'rate' => $user->rate
        ], 200);
    }

    public function loserRanking()
    {
        $user = DB::table('users')->orderBy('rate')->first();
    
        return response()->json([
            'user' => $user->name,
            'rate' => $user->rate
        ], 200);
    }
    
}