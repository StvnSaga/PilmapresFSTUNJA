@props(['criterion', 'value', 'catatan', 'disabled' => false])

@php
    $hasCatatan = !empty($catatan);
@endphp

<div class="scoring-row mb-3">
    <div class="row align-items-center">
        <div class="col-sm-7">
            <label class="form-label mb-0">
                {{ $criterion['label'] }}<br>
            </label>
        </div>
        <div class="col-sm-5 d-flex align-items-center">
            <input type="number" name="skor[{{ $criterion['key'] }}]"
                   class="form-control form-control-sm score-input"
                   data-bobot="{{ $criterion['bobot'] }}"
                   data-bagian="{{ substr($criterion['key'], 0, 1) }}"
                   placeholder="5-10" min="5" max="10"
                   value="{{ $value }}" required
                   {{ $disabled ? 'disabled' : '' }}>
            
            <a href="#" class="text-secondary ms-2 comment-toggle-btn {{ $hasCatatan ? 'text-primary' : '' }}" title="Tambah/Lihat Catatan">
                <i class="bi bi-chat-left-text-fill"></i>
            </a>
        </div>
    </div>
    
    <div class="comment-container mt-2" style="display: {{ $hasCatatan ? 'block' : 'none' }};">
        <textarea name="catatan_gk[{{ $criterion['key'] }}]" class="form-control form-control-sm comment-textarea" rows="2" placeholder="Tulis catatan untuk poin ini..." {{ $disabled ? 'disabled' : '' }}>{{ $catatan }}</textarea>
    </div>
</div>
