@props(['item', 'value', 'catatan', 'disabled' => false])

@php
    $hasCatatan = !empty($catatan);
@endphp

<div class="scoring-row form-group mb-4">
    <label class="form-label"><strong>{{ $item['label'] }}</strong></label>
    <div class="input-group">
        <input type="number" name="skor[{{ $item['field'] }}]" data-field="{{ $item['field'] }}"
               class="form-control score-input" placeholder="Skor ({{ $item['min'] }}-{{ $item['max'] }})"
               min="{{ $item['min'] }}" max="{{ $item['max'] }}"
               value="{{ $value }}" required
               {{ $disabled ? 'disabled' : '' }}> 
        <span class="input-group-text feedback-span" id="feedback-{{ $item['field'] }}">-</span>
        
        <a href="#" class="input-group-text comment-toggle-btn {{ $hasCatatan ? 'text-primary' : 'text-secondary' }}" title="Tambah/Lihat Catatan">
            <i class="bi bi-chat-left-text-fill"></i>
        </a>
    </div>
    
    <div class="comment-container mt-2" style="display: {{ $hasCatatan ? 'block' : 'none' }};">
        <textarea name="catatan_bi[{{ $item['field'] }}]" class="form-control form-control-sm comment-textarea" rows="2" placeholder="Tulis catatan untuk poin ini..." {{ $disabled ? 'disabled' : '' }}>{{ $catatan }}</textarea> {{-- <-- PERUBAHAN DI SINI --}}
    </div>
</div>
