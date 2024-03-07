<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\Request;




class BookingController extends Controller
{
    //
    public function createBooking(Request $req)
    {
        // Validate the request data
        $req->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Check for overlapping bookings
        $overlap = Booking::where('car_id', $req->car_id)
            ->where(function ($query) use ($req) {
                $query->where(function ($query) use ($req) {
                    $query->where('start_date', '>=', $req->start_date)
                        ->where('start_date', '<', $req->end_date);
                })->orWhere(function ($query) use ($req) {
                    $query->where('end_date', '>', $req->start_date)
                        ->where('end_date', '<=', $req->end_date);
                })->orWhere(function ($query) use ($req) {
                    $query->where('start_date', '<', $req->start_date)
                        ->where('end_date', '>', $req->end_date);
                });
            })
            ->exists();
        if ($overlap) {
            return back()
                ->withErrors(['overlap' => 'Booking dates overlap with an existing booking.'])
                ->withInput();
        }
        $start_date = new DateTime($req->start_date);
        $end_date = new DateTime($req->end_date);
        $car = Car::find($req->car_id);
        $renter = User::find($req->renter_id);
        $owner = User::find($req->owner_id);
        $total_days = $start_date->diff($end_date)->days;
        $total_price = $car->price * $total_days;

        $booking = Booking::create([
            "owner_id" => $owner->id,
            "renter_id" => $renter->id,
            "car_id" => $car->id,
            "start_date" => $req->start_date,
            "end_date" => $req->end_date,
            "total_price" => $total_price,
            "accepted" => null,
            "completed" => false
        ]);
        try {
            $booking->save();
        } catch (Exception $e) {
            return back()->with(["msg" => "Failed to create booking"]);
        }
        return view("bookingSuccess")->with('booking', $booking)->with('renter', $renter)->with('owner', $owner)->with('car', $car);
    }
}
