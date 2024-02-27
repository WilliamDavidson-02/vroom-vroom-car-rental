<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\FakeCar;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakeCar($this->faker));
        $vehicle = $this->faker->vehicleArray(); // generate matching brand and model

        return [
            "brand" => $vehicle["brand"],
            "model" => $vehicle["model"],
            "type" => str_replace(' ', '_', $this->faker->vehicleType),
            "door_count" => $this->faker->vehicleDoorCount,
            "seat_count" => $this->faker->vehicleSeatCount,
            "gear_box" => $this->faker->vehicleGearBoxType === "automatic",
            "year" => fake()->biasedNumberBetween(1990, date('Y'), 'sqrt'),
            "hp" => fake()->numberBetween(90, 700),
            "fuel" => $this->faker->vehicleFuelType,
            "fuel_efficiency" => fake()->numberBetween(1, 20),
            "registration" => $this->faker->vehicleRegistration,
            "price" => fake()->numberBetween(50, 400),
            "drive" => fake()->randomElement(["fwd", "rwd", "awd"]),
            "available" => true,
            'user_id' => fake()->randomElement(User::pluck('id')),
        ];
    }
}
