<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class DashboardController extends Controller
{
    public function index(request $request)
    {
        $user = User::find(Auth::id());
        $cars = $user->cars;
        $bookings = Booking::where('owner_id', $user->id)->get();
        $bestPerformer = Car::where('user_id', $user->id)
            ->select('cars.*', DB::raw('(SELECT SUM(total_price) FROM bookings WHERE bookings.car_id = cars.id) as total_cost_of_bookings'))
            ->whereHas('bookings', function ($query) {
                $query->whereYear('start_date', now()->year)
                    ->whereYear('end_date', now()->year);
            })
            ->orderByDesc('total_cost_of_bookings')
            ->first();



        //total nr of reviews
        $numberOfReviews = 0;

        $accepted = 0;
        $declined = 0;
        $pending = 0;

        $totalEarned = 0;

        if (!empty($bookings)) {
            foreach ($bookings as $booking) {
                $totalEarned += $booking->total_price;
                if ($booking->accepted == true) {
                    $accepted += 1;
                } elseif ($booking->accepted == null) {
                    $pending += 1;
                } elseif ($booking->accepted == false) {
                    $declined += 1;
                }
            }
        }

        foreach ($user->cars() as $car) {
            $numberOfReviews += $car->reviews()->count();
        }

        $date = $request->has("date") ? $request->date : date("Y-m");


        $labels = [];
        $carCount = [];
        $totalBookings = 0;
        foreach ($cars as $car) {
            $labels[] = "$car->brand $car->model";
            $bookingCounter = 0;
            foreach ($car->bookings as $booking) {
                $bookingMonth = date("Y-m", strtotime($booking->start_date));
                if ($bookingMonth == $date) {
                    $bookingCounter++;
                }
            }
            $carCount[] = $bookingCounter;
            $totalBookings += $bookingCounter;
        }

        $carPerformances = [
            "labels" => $labels,
            "carCount" => $carCount
        ];



        // Access the total cost of bookings for the best performer car
        $totalCostOfBookings = isNull($bestPerformer) ? 0 : $bestPerformer->total_cost_of_bookings;

        return view("userDashboard")->with("user", $user)->with("bestPerformer", $bestPerformer)->with('totalCostOfbookings', $totalCostOfBookings)->with("numberOfReviews", $numberOfReviews)->with("carPerformances", $carPerformances)->with("date", $date)->with("totalBookings", $totalBookings)->with("declined", $declined)->with("pending", $pending)->with("accepted", $accepted)->with("totalEarned", $totalEarned);
    }
}
