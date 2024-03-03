<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_loaded(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_login_user(): void
    {
        $user = User::factory()->create();

        $res = $this->post("/login", [
            "email" => $user->getAttribute("email"),
            "password" => "password123"
        ]);

        $res->assertRedirect("/");
    }
}
