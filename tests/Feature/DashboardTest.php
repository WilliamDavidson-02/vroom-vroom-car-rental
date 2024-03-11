<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;



class DashboardTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    private $BASE_URI = "/userDashboard";
    public function test_user_dashboard_loads(): void
    {
        // Create a logged in user
        $user = User::factory()->create();

        Auth::login($user);

        $res = $this->get($this->BASE_URI);

        $res->assertStatus(200);
    }
}
