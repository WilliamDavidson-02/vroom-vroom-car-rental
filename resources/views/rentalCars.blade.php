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
                <input required type="date" id="start_date" name="start_date" placeholder="" required
                    min="{{ date('Y-m-d') }}">
                <label for="start_date">Start Date</label>
            </div>
            <div class="input-container">
                <input required type="date" id="end_date" name="end_date" placeholder="" required
                    min="{{ date('Y-m-d', time() + 86400) }}">
                <label for="end_date">End Date</label>
            </div>

            <div class="input-container">
                <select name="country" id="country">
                    @foreach ($countries as $country)
                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <label for="country">Country</label>
            </div>
            <button type="submit">Filter</button>
            @include('components.error')
        </form>

        <div class="total-cars">
            @if ($cars != null)
                {{ 'Total cars: ' . count($cars) }}
            @else
                {{ 'Total cars: ' . 0 }}
            @endif
        </div>
        @if ($cars != null)
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

                    @if ($start_date != null && $end_date != null)
                        <a
                            href="/rentalCars/{{ $car->id }}?start_date={{ $start_date }}&end_date={{ $end_date }}">
                        @else
                            <a href="/rentalCars/{{ $car->id }}">
                    @endif
                    <div class="car-card">

                        <div class="car-image">
                            {{-- $car->image --}}
                            @if ($car->image == null)
                                <img src="images/cars/default_car.svg" alt="">
                            @else
                                <img src="images/cars/{{ $car->image }}" alt="">
                            @endif
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
                                                Automatic
                                            @else
                                                Manual
                                            @endif

                                        </div>
                                        <div class="small-info seats"><img src="/images/person.svg"
                                                alt="">{{ $car->seat_count }}
                                        </div>
                                        <div class="small-info door"><img src="/images/door.svg"
                                                alt="">{{ $car->door_count }}</div>
                                        <div class="small-info hp"><img src="/images/hp.svg"
                                                alt="">{{ $car->hp }}
                                            hp
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
        @endif
    </main>
@endsection
