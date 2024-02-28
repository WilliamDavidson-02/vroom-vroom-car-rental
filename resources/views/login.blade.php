@extends('layout')

@section('title', '- Login')

@section('content')
    <main class="auth-form">
        <form method="post" action="/login">
            @csrf
            <div class="input-container">
                <input required type="email" id="email" name="email" autocomplete="email" placeholder=" ">
                <label for="email">Email</label>
            </div>
            <div class="input-container">
                <input required type="password" id="password" name="password" autocomplete="current-password" placeholder=" ">
                <label for="password">Password</label>
            </div>
            <button type="submit">Login</button>
            @include("error")
            <div class="form-tab-link">
                <span>Don't have an account? <a href="/register">Register</a></span>
            </div>
        </form>
    </main>
@endsection
