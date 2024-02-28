<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $countries = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/countries.json")) ?? [];

        return view("register")->with("countries", $countries);
    }

    public function createAccount(Request $req)
    {
        $req->validate([
            'first_name' => ["required", "string", "min:2"],
            'last_name' => ["required", "string", "min:2"],
            'email' => ["required", "string", "email", "unique:users,email"],
            'phone_number' => ["required"],
            'age' => ['required', 'integer', 'min:18', 'max:100'],
            'country' => ['required', 'string'],
            'password' => ["required", "string", "min:8"],
        ]);

        $user = User::create([
            'first_name' => $req->first_name,
            'last_name' => $req->first_name,
            'email' => $req->email,
            'phone_number' => $req->phone_number,
            'age' => intval($req->age),
            'country' => $req->country,
            'password' => Hash::make($req->password),
        ]);

        Auth::login($user);

        return redirect("/");
    }
}
