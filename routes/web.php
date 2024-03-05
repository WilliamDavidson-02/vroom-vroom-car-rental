<?php

use App\Http\Controllers\DashboardRentalsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get("login", [LoginController::class, "index"])->middleware("guest")->name('login');
Route::post("login", [LoginController::class, "login"])->middleware("guest");

Route::get("register", [RegisterController::class, "index"])->middleware("guest")->name('register');
Route::post("register", [RegisterController::class, "createAccount"])->middleware("guest");

Route::get("logout", LogoutController::class)->middleware("auth")->name('logout');

Route::get("profile", [ProfileController::class, "index"])->middleware("auth")->name('profile');;
Route::patch("profile", [ProfileController::class, "update"])->middleware("auth");

Route::group(['prefix' => 'dashboard'], function () {
    Route::get("/my-rentals", [DashboardRentalsController::class, 'myRentals']);
    Route::get("/my-rentals/add", [DashboardRentalsController::class, 'addNewRental']);
    Route::post("/my-rentals/add", [DashboardRentalsController::class, 'createRental']);
    Route::get("/my-rentals/{car}/remove", [DashboardRentalsController::class, 'removeRental']);
    Route::get("/my-rentals/{car}", [DashboardRentalsController::class, 'myRental']);
    Route::patch("/my-rentals/{car}/update", [DashboardRentalsController::class, 'updateRental']);
})->middlewareGroup("dashboard", ["auth"]);
