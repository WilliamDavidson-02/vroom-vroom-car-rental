<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // prevent owner and renter to have same id
        $car = Car::query()->inRandomOrder()->first();
        $ownerId = $car->user_id;
        $renterId = User::where("id", "!=", $ownerId)->pluck("id")->random();

        // Find a start and end date that does not overlap with other bookings
        $latestBooking = Booking::where("car_id", $car->user_id)->orderBy("end_date", "desc")->first();

        $startDate = new \DateTime(fake()->dateTimeBetween($latestBooking->end_date ?? date("Y-m-d"), "+1 year")->format("Y-m-d"));
        $endDate = new \DateTime(fake()->dateTimeBetween($startDate, $startDate->format("Y-m-d H:i:s") . "+1 week")->format("Y-m-d"));

        // Calculate total price for the car
        $days = $startDate->diff($endDate)->days;
        $totalPrice = $car->price * ($days + 1);

        return [
            "owner_id" => $ownerId,
            "renter_id" => $renterId,
            "car_id" => $car->id,
            "start_date" => $startDate,
            "end_date" => $endDate,
            "total_price" => $totalPrice,
            "accepted" => fake()->randomElement([true, false, null]),
        ];
    }
}
