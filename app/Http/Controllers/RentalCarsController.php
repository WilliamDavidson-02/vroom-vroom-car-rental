<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Car;
use Illuminate\Http\Request;


class RentalCarsController extends Controller
{

    public function index(Request $request)
    {
        $cars = Car::all();
        $countries = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/countries.json")) ?? [];

        return view('rentalCars')->with([
            'cars' => $cars,
            'countries' => $countries
        ]);
    }


    public function filterCars(Request $request)
    {
        if (!$request->has('start_date') || !$request->has('end_date')) {
            return back()->withErrors(["msg" => "You need both Start and End date, country is optional"]);
        } else {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $country = $request->country;
            $countries = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/countries.json")) ?? [];


            $cars = Car::with('bookings')->get()->filter(function ($car) use ($startDate, $endDate, $country) {
                return $car->availableForBooking($startDate, $endDate, $country)->isEmpty();
            });
            return view('rentalCars')->with([
                'cars' => $cars,
                'countries' => $countries
            ]);
        }
    }
}
