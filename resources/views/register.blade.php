@extends('layout')

@section('title', '- Login')

@section('content')
    <main class="auth-form">
        <form method="post" action="/register" enctype="multipart/form-data">
            @csrf
            <label tabindex="0" class="avatar-input" id="avatar-drop-zone" for="avatar">
                @include("avatar")
                <input
                type="file"
                id="avatar"
                name="avatar"
                accept="image/png, image/jpeg, image/jpg, image/svg"
                hidden
                >
            </label>
            <div class="input-container">
                <input min="2" required type="text" id="first_name" name="first_name" autocomplete="name" placeholder=" ">
                <label for="first_name">First name</label>
            </div>
            <div class="input-container">
                <input min="2" required type="text" id="last_name" name="last_name" autocomplete="name" placeholder=" ">
                <label for="last_name">Last name</label>
            </div>
            <div class="input-container">
                <input required type="email" id="email" name="email" autocomplete="email" placeholder=" ">
                <label for="email">Email</label>
            </div>
            <div class="input-container">
                <input required type="tel" id="phone_number" name="phone_number" autocomplete="cc-number" placeholder=" ">
                <label for="phone_number">Phone number</label>
            </div>
            <div class="auth-grid">
                <div class="input-container">
                    <input min="18" max="100" required type="number" id="age" name="age" autocomplete="age" placeholder=" ">
                    <label for="age">Age</label>
                </div>
                    <select autocomplete="country" class="input-container" name="country" id="country">
                        @foreach ($countries as $country)
                            <option value="{{$country->code}}">{{$country->name}}</option>
                        @endforeach
                    </select>
            </div>
            <div class="input-container">
                <input min="8" required type="password" id="password" name="password" autocomplete="new-password" placeholder=" ">
                <label for="password">Password</label>
            </div>
            <button type="submit">Register</button>
            @include("error")
            <div class="form-tab-link">
                <span>Alredy have an account? <a href="/login">Login</a></span>
            </div>
        </form>
    </main>
@endsection
