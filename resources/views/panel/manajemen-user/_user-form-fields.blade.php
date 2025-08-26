{{-- File ini berisi field yang sama untuk form tambah dan edit --}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_name">Nama Lengkap</label>
            <input type="text" class="form-control" id="{{ $prefix }}_name" name="name" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_email">Email</label>
            <input type="email" class="form-control" id="{{ $prefix }}_email" name="email" required>
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
                <option value="panitia">Panitia</option>
                <option value="juri">Juri</option>
            </select>
        </div>
    </div>
</div>