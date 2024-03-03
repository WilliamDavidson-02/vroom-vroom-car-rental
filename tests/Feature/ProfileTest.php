<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_guest_redirect(): void
    {
        $res = $this->get('/profile');

        $res->assertStatus(302);
    }

    public function test_profile_loaded_as_user(): void
    {
        $user = User::factory()->create();

        $this->post("/login", [
            "email" => $user->getAttribute("email"),
            "password" => "password123"
        ]);

        $this->get("/profile")->assertStatus(200);
    }
}
