<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reviews = json_decode(file_get_contents(__DIR__ . "/../../resources/lib/faker_reviews.json"), true);
        $review = $reviews[rand(0, count($reviews) - 1)];
        $booking = Booking::query()->inRandomOrder()->first();

        return [
            "rating" => $review["rating"],
            "description" => $review["description"],
            "user_id" => $booking->renter_id,
            "car_id" => $booking->car_id,
        ];
    }
}
