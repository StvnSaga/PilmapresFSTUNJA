@props(['label', 'weightedScore', 'maxWeightedScore'])

<div class="mb-2">
    <span>{{ $label }}</span>
    <div class="progress progress-primary">
        <div class="progress-bar" role="progressbar"
             style="width: {{ $weightedScore }}%"
             aria-valuenow="{{ $weightedScore }}"
             aria-valuemin="0"
             aria-valuemax="{{ $maxWeightedScore }}">
        </div>
    </div>
</div>