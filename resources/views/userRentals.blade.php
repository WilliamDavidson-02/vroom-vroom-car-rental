@extends('layout')

@section('title', '- My Rentals')

@section("menu")
    @include("components.navigation")
@endsection

@section("content")
<main class="dashboard-my-rentals">
    <a href="/dashboard/my-rentals/add">Add new car</a>
    <section class="car-grid">
        @foreach ($cars as $car)
            <a class="car-card" href="/dashboard/my-rentals/{{$car->id}}">
                @include("components.carImage")
                <div>
                    <div>
                        <span>{{$car->brand}}</span>
                        <span>{{$car->model}}</span>
                    </div>
                    <div class="vehicle-data">
                        <span>{{str_replace("_", " ", $car->type)}}</span>
                        <span>
                            <img class="icon" src="/images/gear.svg" alt="gear box">
                            {{$car->gear_box ? "A" : "M"}}
                        </span>
                        <span>
                            <img class="icon" src="/images/person.svg" alt="seats">
                            {{$car->seat_count}}
                        </span>
                        <span>
                            <img class="icon" src="/images/door.svg" alt="doors">
                            {{$car->door_count}}
                        </span>
                        <span>
                            <img class="icon" src="/images/gauge.svg" alt="fuel efficiency">
                            {{$car->fuel_efficiency}}l/100km
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </section>
</main>
@endsection
