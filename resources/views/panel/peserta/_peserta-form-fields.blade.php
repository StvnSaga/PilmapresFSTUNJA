<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_nama_lengkap">Nama Lengkap</label>
            <input type="text" id="{{ $prefix }}_nama_lengkap" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_nim">NIM</label>
            <input type="text" id="{{ $prefix }}_nim" name="nim" class="form-control" value="{{ old('nim') }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_prodi">Program Studi</label>
            <select class="form-select" id="{{ $prefix }}_prodi" name="prodi" required>
                <option value="">-- Pilih Program Studi --</option>
                @foreach($prodiList as $prodi)
                    <option value="{{ $prodi }}" @if(old('prodi') == $prodi) selected @endif>{{ $prodi }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_angkatan">Angkatan</label>
            <input type="number" id="{{ $prefix }}_angkatan" name="angkatan" class="form-control" placeholder="Contoh: 2021" value="{{ old('angkatan') }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_email">Email</label>
            <input type="email" id="{{ $prefix }}_email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_no_hp">No. HP</label>
            <input type="number" id="{{ $prefix }}_no_hp" name="no_hp" class="form-control" value="{{ old('no_hp') }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_ipk">IPK</label>
            <input type="text" id="{{ $prefix }}_ipk" name="ipk" class="form-control" placeholder="Contoh: 3.75" value="{{ old('ipk') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="{{ $prefix }}_foto">Foto Peserta @if($prefix == 'tambah')  @else (Opsional) @endif</label>
            <input type="file" id="{{ $prefix }}_foto" name="foto" class="form-control" accept="image/jpeg,image/png" @if($prefix == 'tambah') required @endif>
            @if($prefix == 'edit')
            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
            @endif
        </div>
    </div>
</div>
