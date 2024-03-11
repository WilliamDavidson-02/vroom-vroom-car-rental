@extends('layout')

@section('title', '- Requests')

@section("menu")
    @include("components.navigation")
@endsection

@section("content")
    <main class="rental-request">
        <div class="title-container">
            <a title="Requests" href="{{route("requests")}}">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1>#{{$booking->id}}</h1>
        </div>
        <div class="car-container">
            <div class="image-container" id="image-container">
                <div class="skeleton"></div>
                <img loading="lazy" src="/images/cars/{{$car->image ?? "default_car.svg"}}" alt="car">
            </div>
            <div
            class="content{{$booking->accepted === 0 ? " decline " : ""}}{{$booking->accepted === 1 ? " accept " : ""}}"
            >
                <div class="header">{{$car->brand . " " . $car->model}}</div>
                <span>
                    <strong>Start</strong>
                    <div>{{$booking->start_date}}</div>
                </span>
                <span>
                    <strong>End</strong>
                    <div>{{$booking->end_date}}</div>
                </span>
            </div>
        </div>
        <div class="renter">
            <div class="header">
                <div id="image-container" class="avatar">
                    <div class="skeleton"></div>
                    <img loading="lazy" src="/images/avatars/{{$renter->avatar ?? "default_user.svg"}}" alt="">
                </div>
                <span>{{$renter->first_name . " " . $renter->last_name}}</span>
            </div>
            <ul>
                <li>
                    <strong>Age</strong>
                    <span>{{$renter->age}}</span>
                </li>
                <li>
                    <strong>Previous rentals on wroom</strong>
                    <span>{{$renter->prevBookings}}</span>
                </li>
                <li>
                    <strong>Phone number</strong>
                    <span>{{$renter->phone_number}}</span>
                </li>
                <li>
                    <strong>Email</strong>
                    <span>
                        <a href="mailto:{{$renter->email}}">{{$renter->email}}</a>
                    </span>
                </li>
            </ul>
        </div>
        @if ($booking->accepted === null)
            <div class="actions-container">
                <a class="accept" href="">
                    <i class="fa-solid fa-check"></i>
                </a>
                <a class="decline" href="">
                    <i class="fa-solid fa-x"></i>
                </a>
            </div>
        @elseif ($booking->accepted === 0)
            <div class="status">Declined</div>
        @else
            <div class="status">Accepted</div>
        @endif
    </main>
@endsection
