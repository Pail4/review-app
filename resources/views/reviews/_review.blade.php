<div class="review" data-id="{{ $review->id }}">
    <div><strong>{{ e($review->name) }}</strong> — <span>{{ $review->created_at->diffForHumans() }}</span></div>
    <div>Оценка: {{ $review->rating }} / 5</div>
    <div>{{ e($review->body) }}</div>
    <hr>
</div>
