{{-- resources/views/components/criteria-card.blade.php --}}
@props(['title', 'percentage'])

<div class="p-4 rounded-4 text-white mb-4 criteria-card">
    <h4 class="fw-bold">{{ $title }} ({{ $percentage }})</h5>
    <p class="mb-0 small section-text">{{ $slot }}</p>
</div>