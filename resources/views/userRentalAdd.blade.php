@extends('layout')

@section('title', '- Add new rental')

@section("menu")
    @include("components.navigation")
@endsection

@section("content")
<main class="my-rental">
    <form method="post" action="" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <label tabindex="0" class="car-image-dropzone" id="image-dropzone" for="image">
            @include("components.carImage")
            <input
            type="file"
            id="image"
            name="image"
            accept="image/png, image/jpeg, image/jpg, image/svg"
            hidden
            >
        </label>
        <div class="input-container">
            <input min="2" required type="text" id="brand" name="brand" placeholder=" ">
            <label for="brand">Brand</label>
        </div>
        <div class="input-container">
            <input min="2" required type="text" id="model" name="model" placeholder=" ">
            <label for="model">Model</label>
        </div>
        <div class="input-container">
            <select name="type" id="type">
                @foreach ($options->types as $type)
                <option value="{{$type}}">{{ucFirst($type)}}</option>
                @endforeach
            </select>
            <label for="type">Type</label>
        </div>
        <div class="select-grid">
            <div class="input-container">
                <select name="gear_box" id="gear_box">
                    @foreach ($options->gear_boxs as $gear_box)
                    <option value="{{$gear_box->value}}">{{$gear_box->type}}</option>
                    @endforeach
                </select>
                <label for="drive">Gear box</label>
            </div>
            <div class="input-container">
                <select name="drive" id="drive">
                    @foreach ($options->drives as $drive)
                    <option value="{{$drive}}">{{$drive}}</option>
                    @endforeach
                </select>
                <label for="drive">Drive</label>
            </div>
        </div>
        <div class="select-grid">
            <div class="input-container">
                <input min="2" max="5" required type="number" id="door_count" name="door_count" placeholder=" ">
                <label for="door_count">Doors</label>
            </div>
            <div class="input-container">
                <input min="1" max="100" required type="number" id="seat_count" name="seat_count" placeholder=" ">
                <label for="seat_count">Seats</label>
            </div>
        </div>
        <div class="select-grid">
            <div class="input-container">
                <input min="1886" max="{{date("Y")}}" required type="number" id="year" name="year" placeholder=" ">
                <label for="year">Year</label>
            </div>
            <div class="input-container">
                <input min="1" max="9999" required type="number" id="hp" name="hp" placeholder=" ">
                <label for="hp">Hp</label>
            </div>
        </div>
        <div class="select-grid">
            <div class="input-container">
                <select name="fuel" id="fuel">
                    @foreach ($options->fuels as $fuel)
                    <option value="{{$fuel}}">{{ucFirst($fuel)}}</option>
                    @endforeach
                </select>
                <label for="fuel">Fuel</label>
            </div>
            <div class="input-container">
                <input min="0" max="100" step="0.1" required type="number" id="fuel_efficiency" name="fuel_efficiency" placeholder=" ">
                <label for="fuel_efficiency">Fuel efficiency</label>
            </div>
        </div>
        <div class="input-container">
            <input required type="text" id="registration" name="registration" placeholder=" ">
            <label for="registration">Registration</label>
        </div>
        <div class="input-container">
            <input min="1" required type="number" id="price" name="price" placeholder=" ">
            <label for="price">Price (USD)</label>
        </div>
        <div class="input-container">
            <select name="available" id="available">
                <option selected value="1">Available</option>
                <option value="0">Unavailable</option>
            </select>
            <label for="available">Status</label>
        </div>
        <button type="submit">Add</button>
        @include("components.error")
        @include("components.success")

    </form>
</main>
@endsection
