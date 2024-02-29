<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $countries = json_decode(file_get_contents(__DIR__ . "/../../../resources/lib/countries.json")) ?? [];

        return view("profile")->with("countries", $countries)->with("user", Auth::user());
    }

    public function update(Request $req)
    {
        $maxFileSize = 1024 * 5; // 5MB

        $user = User::find(Auth::id());

        $req->validate([
            "avatar" => ["max:$maxFileSize", "mimes:png,jpg,jpeg,svg"],
            'first_name' => ["required", "string", "min:2"],
            'last_name' => ["required", "string", "min:2"],
            'email' => ["required", "string", "email", Rule::unique('users')->ignore($user->id)],
            'phone_number' => ["required"],
            'age' => ['required', 'integer', 'min:18', 'max:100'],
            'country' => ['required', 'string'],
            'password' => ["nullable", "string", "min:8"],
        ]);

        // Update avatar image in users table and public folder
        if ($req->hasFile("avatar")) {
            $avatar = $req->file("avatar");
            $filename = $avatar->hashName();

            $avatarPath = __DIR__ . "/../../../public/images/avatars";

            try {
                // Upload new avatar
                $avatar->move($avatarPath, $filename);

                // Delete old avatar
                if ($user->avatar !== "default_user.svg" && file_exists($avatarPath . "/" . $user->avatar)) {
                    unlink($avatarPath . "/" . $user->avatar);
                }

                // Save new file name
                $user->avatar = $filename;
                $user->save();
            } catch (\Exception $e) {
                return back()->with(["msg" => "Failed to upload new avatar."]);
            }
        }

        // Update remaining data for user
        $data = request()->only(["first_name", "last_name", "email", "phone_number", "age", "country", "password"]);

        // Check if the user has updated there password
        if ($req->filled("password")) {
            $data['password'] = Hash::make($req->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with("success", "Saved successfully");
    }
}
