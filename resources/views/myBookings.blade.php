@extends('layout')

@section('title', '- Profile')
@section('menu')
    @include('components.navigation')
@endsection
@section('content')

    <main class="my-bookings">
        <h1>My Booking Requests</h1>
        <div class="bookings-grid">
            @if (empty($bookings))
                <div class="no-booking">You have made no booking requests.</div>
            @else
                @foreach ($bookings as $booking)
                    <div class="booking-card">

                        <div class="header">
                            <a href="/rentalCars/{{ $booking->car_id }}">
                                {{ $booking->brand }} {{ $booking->model }}
                            </a>
                        </div>
                        <div class="body">
                            <div class="image-container" id="image-container">
                                <div class="skeleton"></div>
                                <img loading="lazy" src="/images/cars/{{ $booking->image ?? 'default_car.svg' }}"
                                    alt="car">
                            </div>
                            <div class="content">

                                <div>
                                    <strong>Start</strong> {{ (new DateTime($booking->start_date))->format('Y-m-d') }}
                                </div>

                                <div>
                                    <strong>End</strong> {{ (new DateTime($booking->end_date))->format('Y-m-d') }}

                                </div>
                                <div class="status">
                                    @if ($booking->accepted === 1)
                                        <div class="accepted">Accepted</div>
                                    @elseif ($booking->accepted === 0)
                                        <div class="declined">Declined</div>
                                    @else
                                        <div class="pending">Pending</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </main>

@endsection
