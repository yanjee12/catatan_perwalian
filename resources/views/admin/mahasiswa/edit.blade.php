@extends('layouts.app')
@section('page-title', 'Edit Data Mahasiswa')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Edit Data Mahasiswa: {{ $mahasiswa->nama }}</h5>

        <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Grup 1: Informasi Dasar -->
            <h6 class="text-primary fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Informasi Dasar</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">NIM <span class="text-danger">*</span></label>
                    <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim', $mahasiswa->nim) }}" required>
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $mahasiswa->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $mahasiswa->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $mahasiswa->no_hp) }}">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Grup 2: Data Akademik -->
            <h6 class="text-primary fw-bold mb-3"><i class="bi bi-mortarboard me-2"></i>Data Akademik</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Dosen Wali</label>
                    <select name="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror">
                        <option value="">-- Pilih Dosen Wali --</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id', $mahasiswa->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('dosen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">IPK</label>
                    <input type="number" step="0.01" min="0" max="4.00" name="ipk" class="form-control @error('ipk') is-invalid @enderror" value="{{ old('ipk', $mahasiswa->ipk) }}" placeholder="0.00">
                    @error('ipk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">SKS Diambil</label>
                    <input type="number" min="0" name="sks_diambil" class="form-control @error('sks_diambil') is-invalid @enderror" value="{{ old('sks_diambil', $mahasiswa->sks_diambil) }}" placeholder="0">
                    @error('sks_diambil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Grup 3: Catatan -->
            <div class="mb-4">
                <label class="form-label fw-bold">Catatan Mahasiswa</label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" placeholder="Masukkan catatan akademik/perwalian...">{{ old('catatan', $mahasiswa->catatan) }}</textarea>
                @error('catatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection