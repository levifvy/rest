<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class GameController extends Controller
{
   
    // public function hasRole($role)
    // {
    //     return $this->roles()->where('name', $role)->exists();
    // }

    private function creatingDataGame($data, $user)
    {
        $game = $user->games()->create($data);
        $user->rate = $user->putRateMark();
        $user->save();

        return $game;
    }

    public function listThrowedGames($id)
    {  
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'This user was not found'], 404);
        }

        if (!Auth::user()->hasRole('admin') && Auth::user()->id != $user->id) {
            return response()->json(['error' => 'This user is unauthorized'], 401);
        }

        return response()->json([
            'player' => $user->nickname,
            'average_rate' => $user->rate,
            'all_games' => $user->games
        ], 200);
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
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'This user was not found'], 404);
        }

        //if (!Auth::user()->hasRole('admin') && Auth::user()->id != $user->id) {
        if (Auth::user()->id != $user->id) {
            return response()->json(['error' => 'This user is unauthorized'], 401);
        }

        $game = $this->creatingDataGame($this->playGame(), $user);

        return response()->json([
            'Result of Throwing' => $game->win ? 'You have won' : 'You have lost',
            'dice_1' => $game->dice_1,
            'dice_2' => $game->dice_2
        ], 200);
    }
    public function deleteAllThrowsOfAPlayer($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'This user was not found'], 404);
        }

        //if (!Auth::user()->hasRole('admin') && Auth::user()->id != $user->id) {
        if (Auth::user()->id != $user->id) {
            return response()->json(['error' => 'This user is unauthorized'], 401);
        }

        $user->games()->delete();
       
        return response()->json(['message' => 'The games belonging to '.$user->nickname.' have been deleted.'], 200);
    }

    // Rankings

    public function indexRanking()
    {
        $average = User::avg('rate');
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
        $user = User::orderByDesc('rate')->first();

        return response()->json([
            'user' => $user->nickname,
            'rate' => $user->rate
        ], 200);
    }

    public function loserRanking()
    {
        $user = User::orderBy('rate')->first();

        return response()->json([
            'user' => $user->nickname,
            'rate' => $user->rate
        ], 200);
    }
}