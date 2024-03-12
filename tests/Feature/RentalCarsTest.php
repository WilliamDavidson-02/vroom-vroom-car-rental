<?php

namespace Tests\Feature;

use App\Models\Booking;
use Tests\TestCase;
use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;


class RentalCarsTest extends TestCase
{
    use RefreshDatabase;

    private $BASE_URI = "/rentalCars";


    public function test_car_rentals_loads(): void
    {
        $user = User::factory()->create();

        Auth::login($user);

        $res = $this->get($this->BASE_URI);

        $res->assertStatus(200);
    }

    // see a specific cars rentalpage

    public function test_specific_car_rental_loads(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $car = Car::factory()->create();

        $res = $this->get($this->BASE_URI . "/" . $car->id);

        $res->assertStatus(200);
        $res->assertSeeText("Reviews");
    }
    // see filtered rentalCars when submitting the form on that page

    public function test_car_rentals_displays_cars(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
        $owner = User::factory()->create();

        $car = Car::factory()->create(["user_id" => $owner->id]);

        $res = $this->post("/rentalCars", [
            'start_date' => '20-01-2024',
            'end_date' => '20-02-2024',
            'country' => 'SE',
        ]);

        $res->assertStatus(200);
        $res->assertSeeText($car->model);
    }
    //create a booking and check that you get the success message
    public function test_book_a_vroom(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
        $owner = User::factory()->create();
        $car = Car::factory()->create(["user_id" => $owner->id]);
        $bookingData = [
            "renter_id" => $user->id,
            "owner_id" => $owner->id,
            "car_id" => $car->id,
            "start_date" => "2024-07-10",
            "end_date" => "2024-07-20",
            "total_price" => 500,
            "accepted" => null
        ];

        $res = $this->post("/createBooking", $bookingData);
        $res->assertSeeText("Your booking is now pending");
    }
}
