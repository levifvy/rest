<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;
use Laravel\Passport\Passport;
use App\Models\User;
    


use PHPUnit\Framework\Test;

use Illuminate\Support\Facades\Hash;
use Tests\Feature\Auth;
use Illuminate\Support\Facades\Artisan;


class GameControllerTest extends TestCase
{
    use RefreshDatabase;

        
        /** @test */ 
        public function test_example(): void
        {
            $response = $this->get('/');

            $response->assertStatus(200);
        }
    
        /** @test */
        public function test_list_throwed_games()
        {
            Passport::actingAs(
                $user = User::factory()->create(),
                ['create-servers']
            );

    
            $response = $this->get(route('players.listThrowedGames', $user->id));
    
            $response->assertStatus(200);
        }
    
        /** @test */
        public function test_throwing_dices()
        {
            Passport::actingAs(
                $user = User::factory()->create(),
                       
            );
    
            $response = $this->actingAs($user)->get(route('players.throwingDices', $user->id));
    
            $response->assertStatus(200);
        }
    
        /** @test */
        public function test_delete_all_throws_of_a_player()
        {
            Passport::actingAs(
                $user = User::factory()->create()->assignRole('player'),
                       
            );
    
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
            Passport::actingAs(
                $admin = User::factory()->create()->assignRole('admin'),
                       
            );

            $response = $this->actingAs($admin, 'api')->json('GET', route('players.indexRanking'));
            
    
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'average_rate_throws_won',
                ]);
        }
    
        /** @test */
        public function test_winner_ranking()
        {

            Passport::actingAs(
                $admin = User::factory()->create()->assignRole('admin'),
                       
            );
            
            $response = $this->actingAs($admin, 'api')->json('GET', route('players.winnerRanking'));
            
            $response->assertStatus(200)->assertJsonStructure([
                'user_with_the_best_average',
                'rate',
            ]);
            $response->Json();
        }
    
        /** @test */
        public function test_loser_ranking()
        {
            Passport::actingAs(
                $admin = User::factory()->create()->assignRole('admin'),
                   
            );
        
            $response = $this->actingAs($admin, 'api')->json('GET', route('players.loserRanking'));
        
            $response->assertStatus(200)->assertJsonStructure([
                'user_with_the_worst_average',
                'rate',
            ]);
            $response->Json();
        }

    /** @test */
    public function test_Number_Is_Equal()
    {
        $myFirstNumber = 1;
        $mySecondNumber = 1;
        $this->assertTrue($myFirstNumber === $mySecondNumber);
    }

}