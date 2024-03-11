<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;


class ReviewController extends Controller
{
    public function createReview(Request $req)
    {
        $req->validate([
            'user_id' => 'required|exists:users,id',
            'booking_id' => 'required|exists:bookings,id',
            'car_id' => 'required|exists:cars,id',
            'rating' => 'required|integer|between:1,5',
            'description' => 'required|string',
        ]);

        $user = User::find($req->user_id);
        $booking = Booking::find($req->booking_id);
        $car = Car::find($req->car_id);

        $review = Review::create([
            "booking_id" => $booking->id,
            "user_id" => $user->id,

            "car_id" => $car->id,
            "rating" => $req->rating,
            "description" => $req->description
        ]);
        $review->save();
        return redirect("/dashboard/myBookings");
    }
}
