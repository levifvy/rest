<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Test;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Auth;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    

    /** @test */  
    public function test_user_is_being_registered_unsuccessfully():void
    {
        Artisan::call('migrate');

        $this->withoutExceptionHandling();

        
        $carry = $this->postJson(route('users.register'));
        $carry->assertStatus(302);

    }


    
    /** @test */
   public function test_user_can_login_successfully():void
   {
       Artisan::call('migrate');
       
       $user = User::factory()->create();

        $response = $this->postJson(route('users.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);
       }

     /** @test */
    public function test_user_is_registered_as_expected():void
    {
       
        Artisan::call('migrate');
        

        $response = $this->postJson(route('users.register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'id',
                'email',
                'Token',
            ]);
    }

    /** @test */
    public function test_update_user_can_do_successfully()
    {
        Passport::actingAs(
            $user = User::factory()->create(),

        );

        $response = $this->putJson(route('players.update', $user->id), [
                'name' => 'John Doe',
            ]);

        $response->assertStatus(200);
        $response->assertOk();
    }

    /** @test */
    public function test_user_can_logout_successfully():void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('players.logout'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'This session was logged out successfully',
            ]);
    }

    /** @test */
    public function test_list_players_rate_get_by_admin():void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->get(route('players.listPlayersRate'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'players' => [
                    '*' => [
                        'nickname',
                        'rate',
                    ],
                ],
            ]);
    }
}