<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_loaded(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_register_user(): void
    {
        $user = User::create([
            "first_name" => "John",
            "last_name" => "Doe",
            "email" => "john.doe@gmail.com",
            "phone_number" => "+467218567",
            "country" => "SE",
            "date_of_birth" => "1995-10-05",
            "password" => "password123"
        ]);

        $this->post("/register", $user->toArray())->assertRedirect("/");

        $this->assertDatabaseHas("users", [
            "email" => $user->getAttribute("email")
        ]);
    }
}
