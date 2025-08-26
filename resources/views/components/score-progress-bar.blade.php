@props(['score'])

<div class="d-flex align-items-center">
    <div class="progress flex-grow-1 score-progressbar">
        <div class="progress-bar" role="progressbar"
             style="width: {{ $score }}%;"
             aria-valuenow="{{ $score }}" aria-valuemin="0"
             aria-valuemax="100">
        </div>
    </div>
    <strong class="ms-2">{{ number_format($score, 2) }}</strong>
</div>