@extends('layout')

@section('title', '- Requests')

@section("menu")
    @include("components.navigation")
@endsection

@section("content")
    <main class="rental-requests">
        {{-- Search form --}}
        <form id="search" autocomplete="off" action="">
            @csrf
            <div class="input-container">
                <input value="{{$start_date ?? ""}}" type="date" id="start_date" name="start_date" placeholder="">
                <label for="start_date">Start Date</label>
            </div>
            <div class="input-container">
                <input value="{{$end_date ?? ""}}" type="date" id="end_date" name="end_date" placeholder="">
                <label for="end_date">End Date</label>
            </div>
            <div class="input-container">
                <select name="car" id="car">
                    <option {{isset($car) && $car === "*" ? "selected" : ""}} value="*">All</option>
                    @foreach ($cars as $currentCar)
                        <option {{isset($car) && $car === $currentCar->id ? "selected" : ""}} value="{{$currentCar->id}}">{{$currentCar->brand}}, {{$currentCar->model}}</option>
                    @endforeach
                </select>
                <label for="car">Car</label>
            </div>
            <div class="input-container">
                <select name="status" id="status">
                    <option {{isset($status) && $status === "*" ? "selected" : ""}} value="*">All</option>
                    <option {{isset($status) && $status === 1 ? "selected" : ""}} value="1">Accepted</option>
                    <option {{isset($status) && $status === 0 ? "selected" : ""}} value="0">Declined</option>
                    <option {{isset($status) && $status === "null" ? "selected" : ""}} value="null">Pending</option>
                </select>
                <label for="status">Status</label>
            </div>
            <button type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        {{-- Pagination --}}
        <div class="pag-container">
            <div id="pag" data-page="{{$currentPage - 1}}">
                <i class="fa-solid fa-chevron-left"></i>
            </div>
            @for ($i = 1; $i <= $pagCount; $i++)
                <div id="pag" data-page="{{$currentPage + $i}}">{{$currentPage + $i}}</div>
            @endfor
            <div id="pag" data-page="{{$currentPage + 1}}">
                <i class="fa-solid fa-chevron-right"></i>
            </div>
        </div>

        {{-- Car grid --}}
        @if (!$bookings->isEmpty())
            <div class="grid">
                @foreach ($bookings as $booking)
                    <div class="card" data-id="{{$booking->id}}">
                        <div class="header">
                            <span>{{$booking->brand}}</span>
                            <span>{{$booking->model}}</span>
                        </div>
                        <div class="body">
                            <div class="image-container" id="image-container">
                                <div class="skeleton"></div>
                                <img loading="lazy" src="/images/cars/{{$booking->image ?? "default_car.svg"}}" alt="car">
                            </div>
                            <div class="content {{$booking->accepted == 1 ? "accept" : ($booking->accepted === 0 ? "decline" : "")}}">
                                <span>
                                    <strong>Start</strong>
                                    <div>{{$booking->start_date}}</div>
                                </span>
                                <span>
                                    <strong>End</strong>
                                    <div>{{$booking->end_date}}</div>
                                </span>
                                @if ($booking->accepted === null)
                                    <div class="space">
                                        <a class="accept" href="{{route("request.choice", ["booking" => $booking->id, "choice" => "accept"])}}">
                                            <i class="fa-solid fa-check"></i>
                                        </a>
                                        <a class="decline" href="{{route("request.choice", ["booking" => $booking->id, "choice" => "decline"])}}">
                                            <i class="fa-solid fa-x"></i>
                                        </a>
                                    </div>
                                @elseif ($booking->accepted === 0)
                                    <div>Declined</div>
                                @else
                                    <div>Accepted</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty">No requests</div>
        @endif
    </main>
    <script>
        const startDate = document.querySelector(".rental-requests #start_date");
        const endDate = document.querySelector(".rental-requests #end_date");

        const pagLinks = document.querySelectorAll(".rental-requests #pag");
        const searchForm = document.querySelector(".rental-requests #search")

        const cars = document.querySelectorAll(".grid .card");

        // Set min date for sibling
        startDate.addEventListener("input", ({ target }) => endDate.min = target.value)
        endDate.addEventListener("input", ({ target }) => startDate.max = target.value)

        // Create serach params
        const serachParams = (page) => {
            // All searchValues from form
            const formData = new FormData(searchForm);
            let params = new URLSearchParams();

            for (const [name, value] of formData.entries()) {
                params.append(name, value);
            }

            params.append("page", page);

            params = params.toString();

            // send params
            window.location.href = `/dashboard/requests?${params}`;
        }

        // Form submit
        searchForm.addEventListener("submit", (ev) => {
            ev.preventDefault();
            serachParams(1)
        });

        // pagination
        pagLinks.forEach(link => {
            const page = link.getAttribute("data-page");

            // Set disabled lins
            if (page < 1 || page > @json($lastPage)) {
                link.disabled = true;
                link.classList.add("disabled")
            }

            link.addEventListener("click", () => {
                if (link.disabled) return;

                serachParams(page);
            })
        })

        cars.forEach(card => {
            const id = card.getAttribute("data-id");

             card.addEventListener("click", () => {
                window.location.href = `/dashboard/requests/${id}`
             })
        })
    </script>
@endsection
