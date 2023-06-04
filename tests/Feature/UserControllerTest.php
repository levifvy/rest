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


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\Feature\faker;
use Illuminate\Support\Str;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    
    

    /** @test */  
    public function test_user_is_being_registered_unsuccessfully()
    {
        Artisan::call('migrate');

        $this->withoutExceptionHandling();

        
        $carry = $this->postJson(route('users.register'));
        $carry->assertStatus(302);

    }


    
    /** @test */
   public function test_user_can_login_successfully()
   {
       Artisan::call('migrate');
       $this->withoutExceptionHandling();
       $user = User::factory()->create();
       $this->actingAs($user);
       $carry = $this->postJson(route('users.login'), ['email' => $user->email, 'password' => 'password']);
       $carry->assertStatus(401);
       }

     /** @test */
    public function test_user_is_registered()
    {
       
        Artisan::call('migrate');
        

        $data = ([  
                    'name' => 'testing',
                    'email' => 'test@testing.es',
                    'password' => Hash::make('123456')
    ]);
    

        $response = $this->postJson(route('users.register'), $data);
         $response->assertStatus[200];

         
    }

    /** @test */
    public function test_Update_User()
    {
        $user = factory(User::class)->create();

        $response = $this->put('/user/' . $user->id, [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function test_user_can_logout_successfully()
    {
        
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->accessToken;
        $headers = ['Authorization' => 'Bearer ' . $token];

        // Hacer la solicitud de logout
        $response = $this->postJson(route('users.logout'), [], $headers);

        
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'This session was logged out successfully',
            ]);

        
        $this->assertNull($user->fresh()->token());
    }
}
