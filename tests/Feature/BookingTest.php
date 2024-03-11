<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;


class BookingTest extends TestCase
{
    use RefreshDatabase;
    private $DASHBOARD_URI = "/dashboard";
    private $MYBOOKINGS_URI = "/myBookings";


    //check if my booking loads 
    public function test_my_bookings_loads(): void
    {
        $user = User::factory()->create();

        Auth::login($user);

        $res = $this->get($this->DASHBOARD_URI . $this->MYBOOKINGS_URI);

        $res->assertStatus(200);
    }

    //check if the created booking exists on the myBookings dashboard
    public function test_check_booking_displays(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        Auth::login($user);

        $car = Car::factory()->create(["user_id" => $owner->id]);

        $booking = Booking::factory()->create([
            'renter_id' => $user->id, 'car_id' => $car->id
        ]);

        $res = $this->get($this->DASHBOARD_URI . $this->MYBOOKINGS_URI);

        $res->assertStatus(200);
        $res->assertSeeText("Start");
    }
    //test to create a booking and then check if it exists in the DB
    public function test_create_booking(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();

        Auth::login($user);

        $car = Car::factory()->create(["user_id" => $owner->id]);
        $booking = Booking::create([
            "owner_id" => $owner->id,
            "renter_id" => $user->id,
            "car_id" => $car->id,
            "start_date" => "2024-03-06T20:24:03.000000Z",
            "end_date" => "2024-03-06T20:24:03.000000Z",
            "total_price" => 500,
            "accepted" => false,
            "completed" => false
        ]);

        $this->post("/createBooking", $booking->toArray())->assertRedirect("/");

        $this->assertDatabaseHas("bookings", [
            "renter_id" => $booking->getAttribute("renter_id")
        ]);
    }
}
