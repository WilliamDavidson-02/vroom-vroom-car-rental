<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;




class ReviewTest extends TestCase
{
    use RefreshDatabase;

    //check to see if creating reviews appears in the DB
    public function test_create_review(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
        $owner = User::factory()->create();
        $car = Car::factory()->create(["user_id" => $owner->id]);
        $booking = Booking::factory()->create();
        $review = ([
            "rating" => 5,
            "description" => "Nice car",
            "user_id" => $user->id,
            "car_id" => $car->id,
            "booking_id" => $booking->id
        ]);

        $this->post("/createReview", $review);

        $this->assertDatabaseHas("reviews", [
            "user_id" => $user->id
        ]);
    }


    // check that the option to submit a review appears when the booking has been "completed"
    public function test_check_reviews(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
        $owner = User::factory()->create();
        $car = Car::factory()->create(["user_id" => $owner->id]);
        $booking = Booking::factory()->create(["owner_id" => $owner->id, "renter_id" => $user->id, "car_id" => $car->id, "accepted" => true]);
        $booking->start_date = "2021-01-01 00:00:00";
        $booking->end_date = "2021-01-02 00:00:00";
        $booking->save();

        $res = $this->get("dashboard/myBookings");
        $res->assertSeeText("$car->model");
        $res->assertSeeText("Create Review for the booking");
    }


    // submit a review and then check the booking page that the notification is there 
    public function test_check_review_submit(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
        $owner = User::factory()->create();
        $car = Car::factory()->create(["user_id" => $owner->id]);
        $booking = Booking::factory()->create(["owner_id" => $owner->id, "renter_id" => $user->id, "car_id" => $car->id, "accepted" => true]);
        $booking->start_date = "2021-01-01 00:00:00";
        $booking->end_date = "2021-01-02 00:00:00";
        $booking->save();

        $review = ([
            "rating" => 5,
            "description" => "Nice car",
            "user_id" => $user->id,
            "car_id" => $car->id,
            "booking_id" => $booking->id
        ]);

        $this->post("/createReview", $review)->assertRedirect("/dashboard/myBookings");
        $res = $this->get("dashboard/myBookings");
        $res->assertSeeText("Thank you for submitting a review");
    }
}
