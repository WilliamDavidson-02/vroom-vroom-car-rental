@extends('layout')

@section('title', '- My Rental')

@section("menu")
    @include("components.navigation")
@endsection

@section("content")
<main class="my-rental">
    <div class="top-container">
        <a title="My rentals" class="back-btn" href="/dashboard/my-rentals">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <a title="Remove" class="remove" href="/dashboard/my-rentals/{{$car->id}}/remove">
            <i class="fa-regular fa-trash-can"></i>
        </a>
    </div>

    {{-- Car form --}}
    <form method="post" action="/dashboard/my-rentals/{{$car->id}}/update" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method("patch")
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
            <input value="{{$car->brand}}" min="2" required type="text" id="brand" name="brand" placeholder=" ">
            <label for="brand">Brand</label>
        </div>
        <div class="input-container">
            <input value="{{$car->model}}" min="2" required type="text" id="model" name="model" placeholder=" ">
            <label for="model">Model</label>
        </div>
        <div class="input-container">
            <select name="type" id="type">
                @foreach ($options->types as $type)
                <option {{$type === str_replace("_", " ", $car->type) ? "selected" : ""}} value="{{$type}}">{{ucFirst($type)}}</option>
                @endforeach
            </select>
            <label for="type">Type</label>
        </div>
        <div class="select-grid">
            <div class="input-container">
                <select name="gear_box" id="gear_box">
                    @foreach ($options->gear_boxs as $gear_box)
                    <option {{$car->gear_box === $gear_box->value ? "selected" : ""}} value="{{$gear_box->value}}">{{$gear_box->type}}</option>
                    @endforeach
                </select>
                <label for="drive">Gear box</label>
            </div>
            <div class="input-container">
                <select name="drive" id="drive">
                    @foreach ($options->drives as $drive)
                    <option {{$drive === $car->drive ? "selected" : ""}} value="{{$drive}}">{{$drive}}</option>
                    @endforeach
                </select>
                <label for="drive">Drive</label>
            </div>
        </div>
        <div class="select-grid">
            <div class="input-container">
                <input value="{{$car->door_count}}" min="2" max="5" required type="number" id="door_count" name="door_count" placeholder=" ">
                <label for="door_count">Doors</label>
            </div>
            <div class="input-container">
                <input value="{{$car->seat_count}}" min="1" max="100" required type="number" id="seat_count" name="seat_count" placeholder=" ">
                <label for="seat_count">Seats</label>
            </div>
        </div>
        <div class="select-grid">
            <div class="input-container">
                <input value="{{$car->year}}" min="1886" max="{{date("Y")}}" required type="number" id="year" name="year" placeholder=" ">
                <label for="year">Year</label>
            </div>
            <div class="input-container">
                <input value="{{$car->hp}}" min="1" max="9999" required type="number" id="hp" name="hp" placeholder=" ">
                <label for="hp">Hp</label>
            </div>
        </div>
        <div class="select-grid">
            <div class="input-container">
                <select name="fuel" id="fuel">
                    @foreach ($options->fuels as $fuel)
                    <option {{$car->fuel === $fuel ? "selected" : ""}} value="{{$fuel}}">{{ucFirst($fuel)}}</option>
                    @endforeach
                </select>
                <label for="fuel">Fuel</label>
            </div>
            <div class="input-container">
                <input value="{{$car->fuel_efficiency}}" min="0" max="100" step="0.1" required type="number" id="fuel_efficiency" name="fuel_efficiency" placeholder=" ">
                <label for="fuel_efficiency">Fuel efficiency</label>
            </div>
        </div>
        <div class="input-container">
            <input value="{{$car->registration}}" required type="text" id="registration" name="registration" placeholder=" ">
            <label for="registration">Registration</label>
        </div>
        <div class="input-container">
            <input value="{{$car->price}}" min="1" required type="number" id="price" name="price" placeholder=" ">
            <label for="price">Price (USD)</label>
        </div>
        <div class="input-container">
            <select name="available" id="available">
                <option {{$car->available}} value="1">Available</option>
                <option {{!$car->available}} value="0">Unavailable</option>
            </select>
            <label for="available">Status</label>
        </div>
        <button type="submit">Save</button>
        @include("components.error")
        @include("components.success")
    </form>

    {{-- Booking chart --}}
    <div class="booking-chart-container">
        <div class="head">
            <span>{{$year}}</span>
            <div>
                <a href="/dashboard/my-rentals/{{$car->id}}?year={{date("Y", mktime(0, 0, 0, 0, 0, $year))}}">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <a href="/dashboard/my-rentals/{{$car->id}}?year={{date("Y")}}">Today</a>
                <a href="/dashboard/my-rentals/{{$car->id}}?year={{date("Y", mktime(0, 0, 0, 0, 0, $year + 2))}}">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <canvas id="booking-chart"></canvas>
    </div>

    {{-- Reviews --}}
    @if (count($reviews))
        @include("components.reviews")
    @else
        <div class="no-reviews">No reviews</div>
    @endif

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('booking-chart').getContext('2d');

    const allEqual = arr => arr.every(val => val === arr[0]);

    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($bookings['labels']),
            datasets: [{
                label: 'Bookings',
                data: @json($bookings['bookings']),
                backgroundColor: "#4e71ba",
                borderColor: '#79a2d8',
                borderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: "#142e6b"
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'All bookings by year for your car.'
                },
            },
            scales: {
                y: {
                    beginAtZero: allEqual(@json($bookings['bookings'])),
                },
            },
            scale: {
                ticks: {
                    precision: 0
                }
            }
        }
    });
</script>
@endsection
