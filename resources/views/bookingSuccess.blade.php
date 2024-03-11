@extends('layout')
@section('title', '- Booking Success')
@section('menu')
    @include('components.navigation')
@endsection
@section('content')
    <main class="booking-success-page">
        <div class="booking-success">
            <div class="booking-success-top">
                <div class="booking-title">Wroom!</div>
                <div class="booking-sub-text">
                    <div class="pending"> Your booking is now pending...
                    </div>
                    Wait for <span>{{ $owner->first_name . ' ' . $owner->last_name }} </span>to confirm your booking.
                </div>
            </div>
            <div class="booking-success-bottom">
                <div class="price"> The total price is: <span>{{ $booking->total_price }}</span></div>
                <div class="if"> If the owner accepts, you will have access to the
                    <span>{{ $car->brand . ' ' . $car->model }}</span> on
                    <span>{{ $booking->start_date }}</span> until <span>{{ $booking->end_date }}</span>!
                </div>
                <div class="check">Remember to check your <a href="{{ route('myBookings') }}">bookings </a> regulary!</div>
                <div class="enjoy"><span>Enjoy your Vroom!</span></div>
            </div>
        </div>
    </main>
@endsection
