<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_name">Nama Lengkap</label>
            <input type="text" class="form-control" id="{{ $prefix }}_name" name="name" value="{{ old('name', $isEdit ? ($user->name ?? '') : '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_email">Email</label>
            <input type="email" class="form-control" id="{{ $prefix }}_email" name="email" value="{{ old('email', $isEdit ? ($user->email ?? '') : '') }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_password">Password {{ $isEdit ? 'Baru (Opsional)' : '' }}</label>
            <input type="password" class="form-control" id="{{ $prefix }}_password" name="password" placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}" {{ !$isEdit ? 'required' : '' }}>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_role">Role</label>
            <select class="form-select" id="{{ $prefix }}_role" name="role" required>
                @if(!$isEdit) <option value="">-- Pilih Role --</option> @endif
                <option value="panitia" {{ old('role', $isEdit ? ($user->role ?? '') : '') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                <option value="juri" {{ old('role', $isEdit ? ($user->role ?? '') : '') == 'juri' ? 'selected' : '' }}>Juri</option>
            </select>
        </div>
    </div>
</div>
<div class="form-group" id="{{ $prefix }}_jenis_juri_container" style="display: none;">
    <label for="{{ $prefix }}_jenis_juri">Spesialisasi Juri</label>
    <select name="jenis_juri" id="{{ $prefix }}_jenis_juri" class="form-select">
        <option value="">-- Pilih Spesialisasi --</option>
        <option value="GK" {{ old('jenis_juri', $isEdit ? ($user->jenis_juri ?? '') : '') == 'GK' ? 'selected' : '' }}>Juri Gagasan Kreatif (GK)</option>
        <option value="BI" {{ old('jenis_juri', $isEdit ? ($user->jenis_juri ?? '') : '') == 'BI' ? 'selected' : '' }}>Juri Bahasa Inggris (BI)</option>
    </select>
</div>

