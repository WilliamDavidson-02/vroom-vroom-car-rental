<?php
use App\Models\User;
?>
@extends('layout')

@section('title', '- rentalCars')

@section('menu')
    @include('components.navigation')
@endsection

@section('content')
    <main class="filter-cars">
        <h1>Rent a Vroom</h1>
        <form class="filter-car" method="post" action="/rentalCars" enctype="multipart/form-data">
            @csrf
            <div class="input-container">
                <input max="10" required type="date" id="start_date" name="start_date" placeholder="" required>
                <label for="start_date">Start Date</label>
            </div>
            <div class="input-container">
                <input min="2" required type="date" id="end_date" name="end_date" placeholder="" required>
                <label for="end_date">End Date</label>
            </div>
            {{-- TODO: fix country filtration 
                <div class="input-container">
                <select name="country" id="country">
                    @foreach ($countries as $country)
                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <label for="country">Country</label>
            </div> --}}
            <button type="submit">Filter</button>
            @include('error')
        </form>

        <div class="total-cars">
            {{ 'Total cars: ' . count($cars) }}
        </div>
        <div class="car-cards">
            @foreach ($cars as $car)
                <?php $user = User::find($car->user_id);
                $carRating = 0;
                $carRatingTotal = 0;
                
                if (count($car->reviews) > 0) {
                    foreach ($car->reviews as $review) {
                        $carRating += $review->rating;
                        $carRatingTotal++;
                    }
                }
                $carRating = $carRatingTotal > 0 ? floor($carRating / $carRatingTotal) : 0;
                ?>
                <a href="/car?id={{ $car->id }}">
                    <div class="car-card">

                        <div class="car-image">
                            {{-- $car->image --}}
                            <img src="/cars/car.jpg" alt="">
                        </div>
                        <div class="car-card-header">
                            <div class="car-card-title">
                                <div class="car-card-model">{{ $car->brand . ' ' . $car->model }}</div>
                                <div class="year">{{ $car->year }}</div>
                            </div>
                            @if (count($car->reviews) > 0)
                                <div class="rating">{{ $carRating }}/5 <i class="fa-solid fa-star"></i></div>
                            @endif
                        </div>
                        <div class="car-card-bottom-section">
                            <div class="left-section">
                                <div class="car-card-info">
                                    <div class="subinfo">
                                        <div class="small-info gearbox"> <img src="/images/gear.svg" alt="">
                                            @if ($car->gear_box == 1)
                                                Manual
                                            @else
                                                Automatic
                                            @endif

                                        </div>
                                        <div class="small-info seats"><img src="/images/person.svg"
                                                alt="">{{ $car->seat_count }}
                                        </div>
                                        <div class="small-info door"><img src="/images/door.svg"
                                                alt="">{{ $car->door_count }}</div>
                                        <div class="small-info hp"><img src="/images/gauge.svg"
                                                alt="">{{ $car->hp }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="right-section">
                                <div class="price">$ <span>{{ $car->price }} </span> /d</div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </main>
@endsection
