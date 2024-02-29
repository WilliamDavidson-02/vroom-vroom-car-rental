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
        $maxFileSize = 1024 * 5; // 5MB

        $req->validate([
            "avatar" => ["max:$maxFileSize", "mimes:png,jpg,jpeg,svg"],
            'first_name' => ["required", "string", "min:2"],
            'last_name' => ["required", "string", "min:2"],
            'email' => ["required", "string", "email", "unique:users,email"],
            'phone_number' => ["required"],
            'age' => ['required', 'integer', 'min:18', 'max:100'],
            'country' => ['required', 'string'],
            'password' => ["required", "string", "min:8"],
        ]);

        $user = User::create([
            'first_name' => htmlspecialchars(trim(ucfirst($req->first_name))),
            'last_name' => htmlspecialchars(trim(ucfirst($req->last_name))),
            'email' => htmlspecialchars(trim(($req->email))),
            'phone_number' => $req->phone_number,
            'age' => intval($req->age),
            'country' => $req->country,
            'password' => Hash::make($req->password),
        ]);

        if ($req->hasFile("avatar")) {
            $avatar = $req->file("avatar");
            $filename = $avatar->hashName();

            $avatarPath = __DIR__ . "/../../../public/images/avatars";

            try {
                // Upload avatar
                $avatar->move($avatarPath, $filename);

                // Save new file name
                $user->avatar = $filename;
                $user->save();
            } catch (\Exception $e) {
            }
        }

        Auth::login($user);

        return redirect("/");
    }
}
