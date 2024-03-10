<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllRentalRequestsController extends Controller
{
    public function index(Request $req): View
    {
        $user = User::find(Auth::id());

        // Get a list of all users cars
        $cars = $user->cars()->select("brand", "model", "id")->get();

        // implode car ids
        $ids = implode(",", array_column($cars->toArray(), "id"));

        // Validate search
        $req->validate([
            "start_date" => ["nullable", "date", "date_format:Y-m-d", "before_or_equal:end_date"],
            "end_date" => ["nullable", "date", "date_format:Y-m-d", "after_or_equal:start_date"],
            "car" => ["nullable", "in:*,$ids"],
            "status" => ["nullable", "in:*,1,0,null"]
        ]);

        $pageLimit = 21;
        $query = Booking::where("owner_id", "=", $user->id)
            ->join("cars", "cars.user_id", "=", "bookings.owner_id")
            ->select(
                "cars.brand",
                "cars.model",
                "cars.image",
                "bookings.start_date",
                "bookings.end_date",
                "bookings.accepted",
                "bookings.id"
            )
            ->orderBy("bookings.start_date", "desc");

        // Request values
        $start_date = $req->start_date;
        $end_date = $req->end_date;
        $car = $req->car;
        $status = $req->status;

        // Conditionaly add req params to query
        if ($start_date) {
            $query->where("bookings.start_date", ">=", $start_date);
        }

        if ($end_date) {
            $query->where("bookings.end_date", "<=", $end_date);
        }

        if ($car && $car !== "*") {
            $car = intval($car);

            $query->where("cars.id", "=", $car);
        }

        if ($req->has("status") && $status !== "*") {
            // Format status value
            if (in_array($status, ["1", "0"])) {
                $status = intval($status);
            } else if ($status === "null") {
                $status = null;
            }

            $query->where("bookings.accepted", "=", $status);

            // Reset null to string for select option on client so be selected
            if ($status === null) {
                $status = "null";
            }
        }

        // Execute query
        $bookings =  $query->paginate($pageLimit);

        // dd($bookings);

        // Pages
        $currentPage = $bookings->currentPage();
        $lastPage = $bookings->lastPage();

        // Direct links to pages, calc if there is 5 or less pages to link
        $pagCount = min($lastPage - $currentPage, 5);

        // Format dates
        foreach ($bookings as $booking) {
            $booking->start_date = date("Y-m-d", strtotime($booking->start_date));
            $booking->end_date = date("Y-m-d", strtotime($booking->end_date));
        }

        return view(
            "allRentalRequests",
            compact(
                "bookings",
                "cars",
                "currentPage",
                "lastPage",
                "pagCount",
                "start_date",
                "end_date",
                "car",
                "status"
            )
        );
    }
}
