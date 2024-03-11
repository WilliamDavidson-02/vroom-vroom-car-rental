<?php

use App\Http\Controllers\AllRentalRequestsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardRentalsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RentalCarsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\myBookingsController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [RentalCarsController::class, "index"]);

Route::get("login", [LoginController::class, "index"])->middleware("guest")->name('login');
Route::post("login", [LoginController::class, "login"])->middleware("guest");

Route::get("register", [RegisterController::class, "index"])->middleware("guest")->name('register');
Route::post("register", [RegisterController::class, "createAccount"])->middleware("guest");

Route::get("logout", LogoutController::class)->middleware("auth")->name('logout');

Route::get("profile", [ProfileController::class, "index"])->middleware("auth")->name('profile');
Route::patch("profile", [ProfileController::class, "update"])->middleware("auth");

Route::get("/userDashboard", [DashboardController::class, 'index'])->middleware("auth")->name("userDashboard");

Route::get('/rentalCars', [RentalCarsController::class, 'index'])->name('rentalCars');
Route::post("/rentalCars", [RentalCarsController::class, "filterCars"]);
Route::get("/rentalCars/{car}", [RentalCarsController::class, "aCar"]);


Route::post('/createBooking', [BookingController::class, 'createBooking'])->middleware('auth');
Route::post('/createReview', [ReviewController::class, 'createReview'])->middleware("auth");

Route::group(['prefix' => 'dashboard', "middleware" => "auth"], function () {
    Route::get("/my-rentals", [DashboardRentalsController::class, 'myRentals'])->name('myRentals');
    Route::get("/my-rentals/add", [DashboardRentalsController::class, 'addNewRental']);
    Route::post("/my-rentals/add", [DashboardRentalsController::class, 'createRental']);
    Route::delete("/my-rentals/{car}/remove", [DashboardRentalsController::class, 'removeRental']);
    Route::get("/my-rentals/{car}", [DashboardRentalsController::class, 'myRental']);
    Route::patch("/my-rentals/{car}/update", [DashboardRentalsController::class, 'updateRental']);

    Route::get("/requests", [AllRentalRequestsController::class, "index"])->name("requests");
    Route::get("/myBookings", [myBookingsController::class, "index"])->middleware("auth")->name("myBookings");
});
