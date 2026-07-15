@props(['rating' => 0, 'fontSize' => '13px'])

@php
    $rating = (float) $rating;
    $fullStars = floor($rating);
    $fraction = $rating - $fullStars;
@endphp

<div class="star-rating-display d-inline-flex align-items-center" style="color: #ffc107; font-size: {{ $fontSize }}; gap: 2px; vertical-align: middle;">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= $fullStars)
            <i class="fas fa-star"></i>
        @elseif ($i == $fullStars + 1 && $fraction > 0)
            <span style="position: relative; display: inline-block; width: 1em; height: 1em; line-height: 1; vertical-align: middle;">
                <i class="far fa-star" style="position: absolute; top: 0; left: 0; color: #dbdbd7; margin: 0; padding: 0;"></i>
                <span style="position: absolute; top: 0; left: 0; width: {{ $fraction * 100 }}%; height: 100%; overflow: hidden; display: inline-block; white-space: nowrap; margin: 0; padding: 0;">
                    <i class="fas fa-star" style="color: #ffc107; margin: 0; padding: 0; display: inline-block;"></i>
                </span>
            </span>
        @else
            <i class="far fa-star" style="color: #dbdbd7;"></i>
        @endif
    @endfor
</div>
