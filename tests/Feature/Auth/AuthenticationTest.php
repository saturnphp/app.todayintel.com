<?php

namespace Tests\Feature\Auth;

use App\Data\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $faker = \Faker\Factory::create();


        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => '<PASSWORD>',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '<PASSWORD>',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $faker = \Faker\Factory::create();


        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => '<PASSWORD>',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $faker = \Faker\Factory::create();

        $user = User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => '<PASSWORD>',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
