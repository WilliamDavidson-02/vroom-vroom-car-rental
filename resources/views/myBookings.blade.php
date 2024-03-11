@extends('layout')

@section('title', '- Profile')
@section('menu')
    @include('components.navigation')
@endsection
@section('content')

    <main class="my-bookings">
        <h1>My Booking Requests</h1>
        @include('components.error')

        @if (count($bookings) < 1)
            <div class="no-booking">You have made no Requests</div>
        @else
            <div class="bookings-grid">
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
                            <div
                                class="content {{ $booking->accepted == 1 ? 'accepted' : ($booking->accepted === 0 ? 'declined' : 'pending') }}">

                                <div>
                                    <strong>Start</strong> {{ (new DateTime($booking->start_date))->format('Y-m-d') }}
                                </div>

                                <div>
                                    <strong>End</strong> {{ (new DateTime($booking->end_date))->format('Y-m-d') }}

                                </div>
                                <div class="status">
                                    @if ($booking->accepted === 1)
                                        <div class="accepted">Accepted</div>
                                        @if (new DateTime($booking->end_date) < new DateTime())
                                            & Completed
                                        @endif
                                    @elseif ($booking->accepted === 0)
                                        <div class="declined">Declined</div>
                                    @else
                                        <div class="pending">Pending</div>
                                    @endif
                                </div>
                            </div>
                            @if (new DateTime($booking->end_date) < new DateTime() && $booking->accepted == true && !$booking->review()->exists())
                                <div class="review">
                                    <button class="accordion">
                                        <div class="text">Create Review for the booking</div>
                                        <i class="fa-solid fa-plus"></i></i>
                                    </button>
                                    <div class="panel">
                                        <form action="/createReview" method="POST" class="review-form">
                                            @csrf
                                            <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" id="car_id" name="car_id"
                                                value="{{ $booking->car_id }}">
                                            <input type="hidden" id="booking_id" name="booking_id"
                                                value="{{ $booking->id }}">
                                            <div class="description">
                                                <label for="description">Comment:</label><br>
                                                <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
                                            </div>
                                            <div class="rating">
                                                <label for="rating">Select rating (out of 5):</label><br>
                                                <input type="radio" id="rating1" name="rating" value="1">
                                                <label for="rating1">1</label>
                                                <input type="radio" id="rating2" name="rating" value="2">
                                                <label for="rating2">2</label>
                                                <input type="radio" id="rating3" name="rating" value="3">
                                                <label for="rating3">3</label>
                                                <input type="radio" id="rating4" name="rating" value="4">
                                                <label for="rating4">4</label>
                                                <input type="radio" id="rating5" name="rating" value="5">
                                                <label for="rating5">5</label><br>
                                            </div>
                                            <button type="submit">Submit Review</button>
                                        </form>
                                    </div>
                                </div>
                            @elseif(new DateTime($booking->end_date) < new DateTime() && $booking->accepted == true && $booking->review()->exists())
                                <div class="review">Thank you for submitting a review!</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </main>

    <script>
        let acc = document.querySelectorAll(".accordion");


        acc.forEach(accordion => {
            accordion.addEventListener("click", function() {
                this.classList.toggle("active");
                let panel = this.nextElementSibling;
                let plus = this.querySelector("i.fa-solid");
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                    plus.classList.remove("fa-minus");
                    plus.classList.add("fa-plus");
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                    plus.classList.remove("fa-plus");
                    plus.classList.add("fa-minus");
                }
            })


        });
    </script>
@endsection
