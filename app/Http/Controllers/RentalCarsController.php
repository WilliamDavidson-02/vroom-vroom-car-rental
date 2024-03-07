<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;




class RentalCarsController extends Controller
{

    public function index(Request $request)
    {
        $start_date = null;
        $end_date = null;
        $cars = Car::all();
        $countries = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/countries.json")) ?? [];

        return view('rentalCars')->with([
            'cars' => $cars,
            'countries' => $countries,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function aCar(Request $request, Car $car)
    {
        $user = Auth::user();
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $rating = 0;
        $reviews = $car->reviews;
        foreach ($reviews as $review) {
            $review->reviewer = User::find($review->user_id);
            $rating += $review->rating;
        }
        $owner = User::find($car->user_id);
        return view("aCar")->with("car", $car)->with("reviews", $reviews)->with("owner", $owner)->with("rating", $rating)->with("start_date", $start_date)->with("end_date", $end_date)->with('user', $user);
    }
    public function filterCars(Request $request)
    {
        if (!$request->has('start_date') || !$request->has('end_date')) {
            return back()->withErrors(["msg" => "You need both Start and End date, country is optional"]);
        } else {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            // TODO: fix country filtering $country = $request->country;
            $countries = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/countries.json")) ?? [];
            $cars = Car::leftJoin('bookings', function ($join) use ($start_date, $end_date) {
                $join->on('cars.id', '=', 'bookings.car_id')
                    ->where(function ($query) use ($start_date, $end_date) {
                        $query->where(function ($subQuery) use ($start_date, $end_date) {
                            $subQuery->whereBetween('bookings.start_date', [$start_date, $end_date])
                                ->orWhereBetween('bookings.end_date', [$start_date, $end_date])
                                ->orWhere(function ($innerSubQuery) use ($start_date, $end_date) {
                                    $innerSubQuery->where('bookings.start_date', '<', $start_date)
                                        ->where('bookings.end_date', '>', $end_date);
                                });
                        });
                    });
            })
                ->whereNull('bookings.id')
                ->orWhereNull('bookings.car_id')
                ->select('cars.*')
                ->get();

            return view('rentalCars')->with([
                'cars' => $cars,
                'countries' => $countries,
                'start_date' => $start_date,
                'end_date' => $end_date
            ]);
        }
    }
}
