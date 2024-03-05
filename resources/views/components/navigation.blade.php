<?php $user = Auth::user(); ?>
{{-- TODO: complete the missing links --}}
<?php
if ($user) {
    $cars = $user ? $user->cars : null;
    $notification = 0;
    foreach ($cars as $car) {
        if (!empty($car->bookings)) {
            foreach ($car->bookings as $booking) {
                if ($booking->accepted == null) {
                    $notification++;
                }
            }
        }
    }
    $rating = 0;
    $total = 0;
    foreach ($cars as $car) {
        if (!empty($car->reviews)) {
            foreach ($car->reviews as $review) {
                $rating += $review->rating;
                $total++;
            }
        }
    }

    $rating = $total > 0 ? $rating / $total : 0;
}
?>

@if ($user)
    <div class="off-screen-menu">
        <div class="user">
            @if ($user->avatar == null || $user->avatar == '')
                <img src="/images/avatars/default_user.png" alt="">
            @else
                <img src="{{ url('/images/avatars/' . $user->avatar . '.png') }}" alt="">
            @endif
            <div class="info">
                <div class="name"> {{ $user->first_name . ' ' . $user->last_name }} </div>
                <div class="rating">
                    @for ($i = 0; $i < 5; $i++)
                        @if (floor($rating) > $i)
                            <i class="fa-solid fa-star"></i>
                        @else
                            <i class="fa-regular fa-star"></i>
                        @endif
                    @endfor
                    <div class="total-rating">({{ $total }})</div>
                </div>
            </div>
        </div>
        <ul class="nav-list">
            <a href="">
                {{-- {{ route('mycars') }} --}}
                <li><i class="fa-solid fa-car"></i> My Cars</li>
            </a>
            <a href="">
                {{-- {{ route('bookings') }} --}}
                <li><i class="fa-solid fa-calendar-days"></i> Bookings</li>
            </a>
            <a href="">
                {{-- {{ route('requests') }} --}}
                <li><i class="fa-solid fa-hand-holding-dollar"></i> requests
                    @if ($notification > 0)
                        <div class="notification">{{ $notification }}</div>
                    @endif

                </li>
            </a>
            <a href="{{ route('profile') }}">
                <li><i class="fa-solid fa-user"></i> Profile</li>
            </a>
            <a href="{{ route('rentalCars') }}">
                {{-- {{ route('browsecars') }} --}}
                <li><i class="fa-solid fa-magnifying-glass"></i> Browse Cars</li>
            </a>
            <a href="{{ route('logout') }}">
                <li><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</li>
            </a>
        </ul>
    </div>
@else
    <div class="off-screen-menu">
        <ul class="nav-list">
            <a href="{{ route('rentalCars') }}">
                {{-- {{ route('browsecars') }} --}}
                <li><i class="fa-solid fa-magnifying-glass"></i>Browse cars</li>
            </a>
            <a href="{{ route('login') }}">
                <li><i class="fa-solid fa-arrow-right-from-bracket"></i>Log in</li>
            </a>
            <a href="{{ route('register') }}">
                <li><i class="fa-solid fa-user-plus"></i> Sign up</li>
            </a>
        </ul>
    </div>
@endif
<nav>
    <div class="logo"><img src="/images/runnerlogo1.png" alt=""></div>
    <div class="ham-menu">
        <span></span>
        <span></span>
        <span></span>
        @if ($user && $notification > 0)
            <div class="notification">{{ $notification }}</div>
        @endif
    </div>
</nav>
