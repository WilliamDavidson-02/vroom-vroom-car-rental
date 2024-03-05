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

        return view("userRentals")->with("cars", $cars);
    }

    public function myRental(Car $car): View | RedirectResponse
    {
        if ($car->user_id !== Auth::id()) {
            return redirect("/dashboard/my-rentals");
        }

        $options = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/car_data.json")) ?? [];

        return view("userRentalUpdate")->with("car", $car)->with("options", $options);
    }

    public function addNewRental(): View
    {
        $options = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/car_data.json")) ?? [];

        return view("userRentalAdd")->with("options", $options);
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
