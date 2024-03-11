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
        $cars = null;
        $countries = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/countries.json")) ?? [];

        return view('rentalCars')->with([

            'countries' => $countries,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'cars' => $cars
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
        $user = Auth::user();
        if (!$request->has('start_date') || !$request->has('end_date')) {
            return back()->withErrors(["msg" => "You need both Start and End date, country is optional"]);
        } else {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $countries = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/countries.json")) ?? [];
            $requestCountry = $request->country;

            $cars = Car::leftJoin('bookings', function ($join) use ($start_date, $end_date) {
                $join->on('cars.id', '=', 'bookings.car_id')
                    ->where(function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('bookings.start_date', [$start_date, $end_date])
                            ->orWhereBetween('bookings.end_date', [$start_date, $end_date])
                            ->orWhere(function ($innerSubQuery) use ($start_date, $end_date) {
                                $innerSubQuery->where('bookings.start_date', '<', $start_date)
                                    ->where('bookings.end_date', '>', $end_date);
                            });
                    });
            })
                ->leftJoin('users', 'users.id', '=', 'cars.user_id')
                ->where(function ($query) use ($requestCountry) {
                    $query->where('users.country', '=', $requestCountry);
                })
                ->where(function ($query) {
                    $query->whereNull('bookings.id')
                        ->orWhereNull('bookings.car_id');
                })
                ->where('cars.user_id', '!=', $user->id)
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
