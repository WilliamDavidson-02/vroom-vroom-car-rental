<?php
use App\Models\User;
?>
@extends('layout')
@section('title', '- Car')
@section('menu')
    @include('components.navigation')
@endsection
@section('content')
    <main>
        <div class="car-page">
            <div class="car-info">
                <div class="car-header">
                    <div class="car-title">
                        <div class="name">{{ $car->brand . ' ' . $car->model }}</div>
                        <div class="year">{{ $car->year }}</div>
                    </div>
                    <div class="car-rating">
                        @if ($rating != 0)
                            {{ $rating / count($reviews) }} /5 <i class="fa-solid fa-star"></i>
                        @else
                        @endif
                    </div>
                </div>
                @include('components.carImage')
                <div class="car-subheader">
                    <div class="type">
                        {{ $car->type }}
                    </div>
                    <div class="price">${{ $car->price }}/day</div>
                </div>
                <div class="subinfo">
                    <div class="small-info gearbox"> <img src="/images/gear.svg" alt="">
                        @if ($car->gear_box == 1)
                            Automatic
                        @else
                            Manual
                        @endif
                    </div>
                    <div class="small-info seats"><img src="/images/person.svg" alt="">{{ $car->seat_count }}
                    </div>
                    <div class="small-info door"><img src="/images/door.svg" alt="">{{ $car->door_count }}</div>
                    <div class="small-info hp"><img src="/images/gauge.svg" alt="">{{ $car->hp }} hp
                    </div>
                    <div class="small-info fuel"><img src="/images/gauge.svg" alt="">{{ $car->fuel }}
                    </div>
                    <div class="small-info fuel_efficiency"><img src="/images/gauge.svg"
                            alt="">{{ $car->fuel_efficiency }}L/100km
                    </div>
                    <div class="small-info registration"><img src="/images/gauge.svg" alt="">
                        {{ $car->registration }}
                    </div>
                    <div class="small-info registration"><img src="/images/gauge.svg" alt="">
                        @if ($car->drive == 'fwd')
                            Frontwheel Drive
                        @elseif ($car->drive == 'rwd')
                            Rearwheel Drive
                        @elseif ($car->drive == 'awd')
                            Fourwheel drive
                        @endif
                    </div>
                </div>

                <div class="owner">
                    @include('components.avatar')
                    <div class="owner-info">
                        <div class="name">{{ $user->first_name . ' ' . $user->last_name }}</div>
                        <div class="country"> {{ $user->country }}</div>
                    </div>

                </div>
                <form action="/bookCar" Method="POST">
                    <input type="hidden" id="user" name="user" value="{{ $user->id }}" />
                    <input type="hidden" id="car" name="car" value="{{ $car->id }}" />
                    <input type="hidden" id="start_date" name="start_date" value="" />
                    <input type="hidden" id="end_date" name="end_date" value="" />
                    <button type="submit" class="Book">Book the Vroom -></button>
                </form>


                @if (count($reviews) != 0)
                    <div class="reviews">
                        <div class="reviews-title">Reviews ({{ count($reviews) }})</div>
                        @foreach ($reviews as $review)
                            <div class="review">
                                <div class="user">
                                    <div class="user-info">
                                        <div class="image">
                                            <div id="image-container" class="avatar">
                                                <div class="skeleton"></div>
                                                <img loading="lazy"
                                                    src="/images/avatars/{{ $review->reviewer->avatar ?? 'default_user.svg' }}"
                                                    alt="">
                                            </div>
                                        </div>
                                        <div class="info">
                                            <div class="username">
                                                {{ $review->reviewer->first_name . ' ' . $review->reviewer->last_name }}
                                            </div>
                                            <div class="rating">
                                                @for ($i = 0; $i < 5; $i++)
                                                    @if (floor($review->rating) > $i)
                                                        <i class="fa-solid fa-star"></i>
                                                    @else
                                                        <i class="fa-regular fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>

                                        </div>
                                    </div>
                                    <div class="time">
                                        <?php $timestamp = $review->created_at;
                                        $timestamp->format('Y-m-d H:i:s');
                                        echo $timestamp; ?>
                                    </div>
                                </div>
                                <div class="comment">{{ $review->description }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="reviews">
                        <div class="reviews-title">Reviews ({{ count($reviews) }})</div>

                        No Reviews yet
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
