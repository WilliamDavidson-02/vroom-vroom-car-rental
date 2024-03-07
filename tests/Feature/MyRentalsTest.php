<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MyRentalsTest extends TestCase
{
    use RefreshDatabase;

    private $BASE_URI = "/dashboard/my-rentals";

    public function test_my_rentals_loads(): void
    {
        // Create a logged in user
        $user = User::factory()->create();

        Auth::login($user);

        $res = $this->get($this->BASE_URI);

        $res->assertStatus(200);
    }

    public function test_my_rentals_add_loads(): void
    {
        // Create a logged in user
        $user = User::factory()->create();

        Auth::login($user);

        $res = $this->get($this->BASE_URI . "/add");

        $res->assertStatus(200);
        $res->assertSeeText("Brand");
    }

    public function test_my_rental_car_loads(): void
    {
        // Create a logged in user
        $user = User::factory()->create();
        Auth::login($user);

        $car = Car::factory()->create();

        $res = $this->get($this->BASE_URI . "/" . $car->id);

        $res->assertStatus(200);
    }

    public function test_my_rentals_add_rental(): void
    {
        // Create a logged in user
        $user = User::factory()->create();
        Auth::login($user);

        // Create a car
        $car = [
            "brand" => "Eagle",
            "model" => "Premier",
            "type" => "MPV",
            "door_count" => 2,
            "seat_count" => 2,
            "gear_box" => false,
            "year" => 2016,
            "hp" => 235,
            "fuel" => "gasoline",
            "fuel_efficiency" => 18,
            "registration" => "OVN-015",
            "price" => 308,
            "drive" => "rwd",
            "available" => true,
            "user_id" => 1,
            "updated_at" => "2024-03-06T20:24:03.000000Z",
            "created_at" => "2024-03-06T20:24:03.000000Z",
            "id" => 1
        ];

        $res = $this->post($this->BASE_URI . "/add", $car);

        $res->assertRedirect($this->BASE_URI . "/" . $car["id"]);
        $res->assertStatus(302);
    }

    public function test_my_rentals_remove_rental(): void
    {
        // Create a logged in user
        $user = User::factory()->create();
        Auth::login($user);

        $car = Car::factory()->create();

        $this->assertDatabaseHas("cars", [
            "registration" => $car->registration
        ]);

        $req = $this->delete($this->BASE_URI . "/" . $car->id . "/remove");

        $req->assertRedirect($this->BASE_URI);
        $this->assertDatabaseEmpty("cars");
    }

    public function test_my_rentals_update_car(): void
    {
        // Create a logged in user
        $user = User::factory()->create();
        Auth::login($user);

        $car = Car::factory()->create();

        // Update car brand
        $car->brand = "Audi";

        $res = $this->patch($this->BASE_URI . "/" . $car->id . "/update", $car->toArray());

        $this->assertDatabaseHas("cars", ["brand" => $car->brand]);
        $res->assertStatus(302);
    }
}
