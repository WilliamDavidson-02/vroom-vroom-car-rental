@extends('layout')

@section('title', '- Profile')
@section('menu')
    @include('components.navigation')
@endsection
@section('content')
    <main class="auth-form">
        <form enctype="multipart/form-data" method="post" action="/profile">
            @csrf
            @method('patch')
            <label tabindex="0" class="avatar-input" id="image-dropzone" for="avatar">
                @include('components.avatar')
                <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg, image/jpg, image/svg"
                    hidden>
            </label>
            <div class="input-container">
                <input value="{{ $user->first_name }}" min="2" required type="text" id="first_name"
                    name="first_name" autocomplete="name" placeholder=" ">
                <label for="first_name">First name</label>
            </div>
            <div class="input-container">
                <input value="{{ $user->last_name }}" min="2" required type="text" id="last_name" name="last_name"
                    autocomplete="name" placeholder=" ">
                <label for="last_name">Last name</label>
            </div>
            <div class="input-container">
                <input value="{{ $user->email }}" required type="email" id="email" name="email"
                    autocomplete="email" placeholder=" ">
                <label for="email">Email</label>
            </div>
            <div class="input-container">
                <input value="{{ $user->phone_number }}" required type="tel" id="phone_number" name="phone_number"
                    autocomplete="tel-local" placeholder=" ">
                <label for="phone_number">Phone number</label>
            </div>
            <div class="input-container">
                <input max="{{ date('Y-m-d', strtotime('-18 years')) }}" value="{{ $user->date_of_birth }}" required
                    type="date" id="date_of_birth" name="date_of_birth" autocomplete="bday" placeholder=" ">
                <label for="date_of_birth">Date of birth</label>
            </div>
            <div class="input-container">
                <select autocomplete="country" name="country" id="country">
                    @foreach ($countries as $country)
                        <option {{ $country->code === $user->country ? 'selected' : '' }} value="{{ $country->code }}">
                            {{ $country->name }}</option>
                    @endforeach
                </select>
                <label for="country">Country</label>
            </div>
            <div class="input-container">
                <input min="8" type="password" id="password" name="password" autocomplete="new-password"
                    placeholder=" ">
                <label for="password">Password</label>
            </div>
            <button type="submit">Save</button>
            @include('components.error')
            @include('components.success')
        </form>
    </main>
@endsection
