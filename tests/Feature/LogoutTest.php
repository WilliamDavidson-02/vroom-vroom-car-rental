<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_user(): void
    {
        $user = User::factory()->create();

        $this->post("/login", [
            "email" => $user->getAttribute("email"),
            "password" => "password123"
        ]);

        $this->get('/logout')->assertRedirect("/login");

        $this->assertGuest();
    }
}
