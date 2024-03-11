<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalRequestController extends Controller
{
    public function index(Booking $booking)
    {
        $user = User::find(Auth::id());

        // Check if the user is the owner
        if ($user->id !== $booking->owner_id) return back();

        $car = Car::where("id", "=", $booking->car_id)->select("brand", "model", "image")->first();
        $renter = User::where("id", "=", $booking->renter_id)->select("first_name", "last_name", "date_of_birth", "country", "phone_number", "email")->first();

        // Calculate age of renter
        $renter->age = date("Y") - date("Y", strtotime($renter->date_of_birth));

        // Get count on renters prev bookings
        $renter->prevBookings = Booking::where("renter_id", "=", $booking->renter_id)->count();

        // Format start/end dates
        $booking->start_date = date("Y-m-d", strtotime($booking->start_date));
        $booking->end_date = date("Y-m-d", strtotime($booking->end_date));

        return view("rentalRequest", compact("booking", "car", "renter"));
    }

    public function choice(Request $req, Booking $booking)
    {
        $user = User::find(Auth::id());

        // Check if the user is the owner
        if ($user->id !== $booking->owner_id) return back();

        // Update accepted
        if ($req->choice === "accept") {
            $booking->accepted = 1;
        } else if ($req->choice === "decline") {
            $booking->accepted = 0;
        }

        $booking->save();

        return back();
    }
}
