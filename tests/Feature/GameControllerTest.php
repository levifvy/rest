<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;

use App\Models\User;
    

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

        /**
         * A basic feature test example.
         */
        /** @test */ 
        public function test_example(): void
        {
            $response = $this->get('/');

            $response->assertStatus(200);
        }
    
        /** @test */
        public function test_list_throwed_games()
        {
            $user = User::factory()->create();
    
            $response = $this->actingAs($user)
                ->get(route('players.listThrowedGames', $user->id));
    
            $response->assertStatus(200)
                ->assertJson([
                    'player' => $user->nickname,
                    'average_rate' => $user->rate,
                    'all_games_from_this_player' => $user->games,
                ]);
        }
    
        /** @test */
        public function test_throwing_dices()
        {
            $user = User::factory()->create();
    
            $response = $this->actingAs($user)
                ->get(route('games.throw', $user->id));
    
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'Result of Throwing',
                    'dice_1',
                    'dice_2',
                ]);
        }
    
        /** @test */
        public function test_delete_all_throws_of_a_player()
        {
            $user = User::factory()->create();
    
            $response = $this->actingAs($user)
                ->delete(route('players.deleteAllThrowsOfAPlayer', $user->id));
    
            $response->assertStatus(200)
                ->assertJson([
                    'message' => 'The games belonging to ' . $user->nickname . ' have been deleted.',
                ]);
        }
    
        /** @test */
        public function test_index_ranking()
        {
            $response = $this->get(route('players.indexRanking'));
    
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'average_rate_throws_won',
                ]);
        }
    
        /** @test */
        public function test_winner_ranking()
        {
            $response = $this->get(route('players.winnerRanking'));
    
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'user_with_the_best_average',
                    'rate',
                ]);
        }
    
        /** @test */
        public function test_loser_ranking()
        {
            $response = $this->get(route('players.loserRanking'));
    
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'user_with_the_worst_average',
                    'rate',
                ]);
        }
    }
    