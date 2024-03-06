<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardRentalsController extends Controller
{
    public function myRentals(Request $req): View
    {
        $user = User::find(Auth::id());
        $cars = $user->cars()->orderBy("created_at", "desc")->get();

        return view("userRentals", compact("cars"));
    }

    public function myRental(Request $req, Car $car): View | RedirectResponse
    {

        if ($car->user_id !== Auth::id()) {
            return redirect("/dashboard/my-rentals");
        }

        $stars = [
            "5" => 0,
            "4" => 0,
            "3" => 0,
            "2" => 0,
            "1" => 0,
            "0" => 0,
        ];

        $limit = $req->limit ?? 10;

        $reviews = $car->reviews()->orderBy("created_at", "desc")->limit($limit)->get();

        // Get total number of reviews
        $count = $car->reviews()->count();

        foreach ($reviews as $review) {
            $stars[$review->rating] += 1;
            $review->reviewer = User::find($review->user_id);
        }

        // Create data for booking chart
        $year = $req->has("year") ? $req->year : date("Y");

        // Check if year is numeric
        if (!is_numeric($year)) {
            $year = date("Y");
        }

        // Get bookings by year
        $bookings = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $data = $car->bookings()->select("start_date")->whereYear("start_date", $year)->get()->toArray();

        // Count bookings for months
        if (!empty($data)) {
            foreach ($data as $booking) {
                $month = intval(date("m", strtotime($booking["start_date"])));

                $bookings[$month - 1] += 1;
            }
        }


        $bookings = [
            "labels" => ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            "bookings" => $bookings
        ];

        $options = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/car_data.json")) ?? [];

        return view("userRentalUpdate", compact('car', 'options', 'reviews', 'stars', 'limit', 'count', 'bookings', 'year'));
    }

    public function addNewRental(): View
    {
        $options = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/car_data.json")) ?? [];

        return view("userRentalAdd", compact("options"));
    }

    public function removeRental(Car $car)
    {
        // Check if user owns car
        if ($car->user_id !== Auth::id()) {
            return redirect("/dashboard/my-rentals");
        }

        // Renmove image
        $imagePath = __DIR__ . "/../../../public/images/cars/";

        try {
            if ($car->image !== "default_car.svg" && file_exists($imagePath . $car->image)) {
                unlink($imagePath . $car->image);
            }
        } catch (\Exception $e) {
            return back()->with(["msg" => "Failed to remove image."]);
        }

        $car->delete();

        return redirect("/dashboard/my-rentals");
    }

    public function createRental(Request $req)
    {
        $maxFileSize = 1024 * 5; // 5MB
        $options = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/car_data.json")) ?? [];

        // Valid values for mimes
        $types = implode(",", $options->types);
        $drives = implode(",", $options->drives);
        $fuels = implode(",", $options->fuels);

        $req->validate([
            "avatar" => ["max:$maxFileSize", "mimes:png,jpg,jpeg,svg"],
            "brand" => ["required", "string"],
            "model" => ["required", "string"],
            "type" => ["required", "string", "in:$types"],
            "gear_box" => ["required", "string", "in:1,0"],
            "drive" => ["required", "string", "in:$drives"],
            "door_count" => ["required", "numeric", "between:2, 5"],
            "seat_count" => ["required", "numeric", "between:1, 100"],
            "year" => ["required", "numeric", "between:1886," . date("Y")],
            "hp" => ["required", "numeric", "between:1,9999"],
            "fuel" => ["required", "string", "in:$fuels"],
            "fuel_efficiency" => ["required", "numeric", "between:1,100"],
            "registration" => ["required", "string"],
            "price" => ["required", "numeric", "min:1"],
            "available" => ["required", "numeric", "in:1,0"]
        ]);

        $data = $req->all();
        unset($data["image"]);
        $data["user_id"] = Auth::id();

        $car = Car::create($data);

        // Update image in cars table and public folder
        if ($req->hasFile("image")) {
            $image = $req->file("image");
            $filename = $image->hashName();

            $imagePath = __DIR__ . "/../../../public/images/cars/";

            try {
                // Upload new image
                $image->move($imagePath, $filename);

                // Delete old image
                if ($car->image !== "default_car.svg" && file_exists($imagePath . $car->image)) {
                    unlink($imagePath . $car->image);
                }

                // Save new file name
                $car->image = $filename;
                $car->save();
            } catch (\Exception $e) {
                return back()->with(["msg" => "Failed to upload new image."]);
            }
        }

        return redirect("/dashboard/my-rentals/" . $car->id)->with("success", "Your new car has bin added to your rentals");
    }

    public function updateRental(Request $req, Car $car)
    {
        $user = User::find(Auth::id());

        // Redirect back if user does not owne the car
        if ($car->user_id !== $user->id) {
            return redirect("/dashboard/my-rentals");
        }

        $maxFileSize = 1024 * 5; // 5MB
        $options = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/car_data.json")) ?? [];

        // Valid values for mimes
        $types = implode(",", $options->types);
        $drives = implode(",", $options->drives);
        $fuels = implode(",", $options->fuels);

        $req->validate([
            "avatar" => ["max:$maxFileSize", "mimes:png,jpg,jpeg,svg"],
            "brand" => ["required", "string"],
            "model" => ["required", "string"],
            "type" => ["required", "string", "in:$types"],
            "gear_box" => ["required", "string", "in:1,0"],
            "drive" => ["required", "string", "in:$drives"],
            "door_count" => ["required", "numeric", "between:2, 5"],
            "seat_count" => ["required", "numeric", "between:1, 100"],
            "year" => ["required", "numeric", "between:1886," . date("Y")],
            "hp" => ["required", "numeric", "between:1,9999"],
            "fuel" => ["required", "string", "in:$fuels"],
            "fuel_efficiency" => ["required", "numeric", "between:1,100"],
            "registration" => ["required", "string"],
            "price" => ["required", "numeric", "min:1"],
            "available" => ["required", "numeric", "in:1,0"]
        ]);

        $data = $req->only(["barnd", "model", "type", "gear_box", "drive", "door_count", "seat_count", "year", "hp", "fuel", "fuel_efficiency", "registration", "price", "available"]);
        $car->update($data);

        // Update image in cars table and public folder
        if ($req->hasFile("image")) {
            $image = $req->file("image");
            $filename = $image->hashName();

            $imagePath = __DIR__ . "/../../../public/images/cars/";

            try {
                // Upload new image
                $image->move($imagePath, $filename);

                // Delete old image
                if ($car->image !== "default_car.svg" && file_exists($imagePath . $car->image)) {
                    unlink($imagePath . $car->image);
                }

                // Save new file name
                $car->image = $filename;
                $car->save();
            } catch (\Exception $e) {
                return back()->with(["msg" => "Failed to upload new image."]);
            }
        }

        return back()->with("success", "Saved successfully");
    }
}
