<section class="reviews-container">
    <h1>Reviews</h1>
    <div class="star-chart">
        @foreach ($stars as $star => $rating)
            <div class="index">{{$star}}</div>
            <div class="fill">
                <div style="width: {{($rating/count($reviews)*100)}}%"></div>
            </div>
        @endforeach
    </div>
    <p class="total">Reviews ({{$count}})</p>
    @foreach ($reviews as $review)
        <div class="review-card">
            <div class="user">
                <div id="image-container" class="avatar">
                    <div class="skeleton"></div>
                    <img loading="lazy" src="/images/avatars/{{$review->reviewer->avatar ?? "default_user.svg"}}" alt="">
                </div>
                <div class="content">
                    <span>{{$review->reviewer->first_name . " " . $review->reviewer->last_name}}</span>
                    <span class="between">
                        <span>
                            @for ($i = 0; $i < $review->rating; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                        </span>
                        <span>{{date("Y-m-d", strtotime($review->created_at))}}</span>
                    </span>
                </div>
            </div>
            <p>{{$review->description}}</p>
        </div>
    @endforeach
    @if (count($reviews) < $count)
        <a class="see-more" href="/dashboard/my-rentals/{{$car->id}}?limit={{min(!$limit ? 20 : $limit + 10, $count)}}">Read more</a>
    @endif
</section>
