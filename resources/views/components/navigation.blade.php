<?php $user = Auth::user(); ?>
{{-- TODO: implement logic to check if user has any requests with the status pending == null, 
    if they do, display a notification symbol on the "requests" menu item and on the hamburger menu-}}
{{-- if $user --}}
@if (true)
    <div class="off-screen-menu">
        <div class="user">
            {{-- if $user->avatar == null || $user->avatar == "" --}}
            @if (true)
                {{-- {{ url('/images/avatars/default_user.png') }} --}}
                <img src="/images/avatars/default_user.png" alt="">
            @else
                {{-- {{ url('/images/avatars/' . $user->avatar . '.png') }} --}}
                <img src="" alt="">
            @endif

            <div class="info">
                {{-- {{ $user->first_name . ' ' . $user->last_name }} --}}
                <div class="name"> John Doe</div>
                <div class="rating">
                    {{-- TODO: implement logic and display full stars depending on how many they have, placeholders for now --}}
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <div class="total-rating">(x)</div>
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
                <li><i class="fa-solid fa-hand-holding-dollar"></i> Requests</li>

            </a>
            <a href="">
                {{-- {{ route('profile') }} --}}
                <li><i class="fa-solid fa-user"></i> Profile</li>
            </a>
            <a href="">
                {{-- {{ route('browsecars') }} --}}
                <li><i class="fa-solid fa-magnifying-glass"></i> Browse Cars</li>
            </a>
            <a href="">
                {{-- {{ route('logout') }} --}}
                <li><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</li>
            </a>
        </ul>


    </div>
@else
    <div class="off-screen-menu">
        <ul>
            <a href="">
                {{-- {{ route('browsecars') }} --}}
                <li>Browse cars</li>
            </a>
            <a href="">
                {{-- {{ route('signup') }} --}}
                <li>Sign up</li>
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
    </div>
</nav>
