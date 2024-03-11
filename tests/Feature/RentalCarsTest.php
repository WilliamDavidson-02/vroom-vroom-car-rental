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


    public function test_specific_car_rental_loads(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $car = Car::factory()->create();

        $res = $this->get($this->BASE_URI . "/" . $car->id);

        $res->assertStatus(200);
        $res->assertSeeText("Reviews");
    }

    //TODO: Write a test to see selected rentalCars when submitting the form on that page
}
