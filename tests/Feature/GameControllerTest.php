<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Game;

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

    <?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    
    /** @test */
    public function test_Create_Game()
    {
        $response = $this->post('/game', [
            'title' => 'New Game',
            'description' => 'This is a new game.',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('games', [
            'title' => 'New Game',
            'description' => 'This is a new game.',
        ]);
    }

   
    /** @test */
    public function test_Update_Game()
    {
        $game = factory(Game::class)->create();

        $response = $this->put('/game/' . $game->id, [
            'title' => 'Updated Game',
            'description' => 'This game has been updated.',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'title' => 'Updated Game',
            'description' => 'This game has been updated.',
        ]);
    }

    /** @test */
    public function test_Delete_Game()
    {
        $game = factory(Game::class)->create();

        $response = $this->delete('/game/' . $game->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('games', [
            'id' => $game->id,
        ]);
    }
}


}