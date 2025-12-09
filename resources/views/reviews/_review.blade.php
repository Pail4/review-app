<div class="review" data-id="{{ $review->id }}">
    <div>
        <strong>{{ e($review->name) }}</strong>
        <span>• {{ $review->created_at->diffForHumans() }}</span>
    </div>

    <div class="review-rating">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $review->rating)
                ⭐
            @else
                ☆
            @endif
        @endfor
    </div>

    <div style="margin-top: 10px;">
        {{ e($review->body) }}
    </div>
</div>
