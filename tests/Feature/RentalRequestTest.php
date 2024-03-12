<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RentalRequestTest extends TestCase
{
    use RefreshDatabase;

    private $BASE_URI = "/dashboard/requests/";

    public function test_requests_load(): void
    {
        // Create a logged in user
        $user = User::factory()->create();
        $this->actingAs($user);

        $res = $this->get($this->BASE_URI);

        $res->assertStatus(200);
    }

    public function test_request_booking_load(): void
    {
        User::factory(2)->create();

        $owner = User::first();
        $this->actingAs($owner);

        // Create a car with out factory to make sure actingAs is the owner
        Car::factory()->create([
            "user_id" => $owner->id
        ]);

        $booking = Booking::factory()->create();

        $res = $this->get($this->BASE_URI . $booking->id);

        $res->assertSeeText("#" . $booking->id);
    }

    public function test_requests_with_filter(): void
    {
        User::factory(2)->create();

        $owner = User::first();
        $this->actingAs($owner);

        // Create a car with out factory to make sure actingAs is the owner
        Car::factory()->create(["user_id" => $owner->id]);

        $booking = Booking::factory()->create([
            "start_date" => date("Y-m-d", strtotime("+1 day")),
            "end_date" => date("Y-m-d", strtotime("+1 day")),
        ]);

        $serach = [
            "start_date" => date("Y-m-d"),
            "end_date" => date("Y-m-d"),
            "car" => "*",
            "status" => "*"
        ];

        $res = $this->get($this->BASE_URI . "?" . http_build_query($serach));

        $res->assertStatus(200);
        $res->assertSeeText("No requests");
    }

    public function test_request_choice(): void
    {
        User::factory(2)->create();

        $owner = User::first();
        $this->actingAs($owner);

        // Create a car with out factory to make sure actingAs is the owner
        Car::factory()->create(["user_id" => $owner->id]);

        $booking = Booking::factory()->create(["accepted" => null]);

        $res = $this->get($this->BASE_URI . $booking->id . "/choice?choice=accept");

        $res->assertStatus(302);
        $this->assertEquals(1, Booking::first()->accepted);
    }
}
