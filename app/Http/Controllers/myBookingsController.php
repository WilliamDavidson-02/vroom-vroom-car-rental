<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class myBookingsController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $bookings = Booking::join("cars", "cars.id", "=", "bookings.car_id")
            ->where("bookings.renter_id", "=", $user->id)
            ->select("bookings.*", "cars.brand", "cars.model", "cars.image")
            ->orderBy("bookings.start_date", "DESC")
            ->get();
        return view("myBookings")->with("bookings", $bookings)->with("user", $user);
    }
}
