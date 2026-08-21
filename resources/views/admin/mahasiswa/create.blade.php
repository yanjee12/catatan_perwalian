@extends('layouts.app')
@section('page-title', 'Tambah Mahasiswa')

@section('content')
<div class="card p-4" style="max-width:600px;">
    
    {{-- Alert Ringkasan Error jika ada validasi gagal --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center mb-1">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Terjadi kesalahan input data!</strong>
            </div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.mahasiswa.store') }}">
        @csrf

        {{-- Input NIM --}}
        <div class="mb-3">
            <label class="form-label">NIM <span class="text-danger">*</span></label>
            <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
            @error('nim')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input Nama Lengkap --}}
        <div class="mb-3">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Row Angkatan & Program Studi --}}
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Angkatan</label>
                <input type="text" name="angkatan" class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan') }}">
                @error('angkatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col mb-3">
                <label class="form-label">Program Studi</label>
                <input type="text" name="program_studi" class="form-control @error('program_studi') is-invalid @enderror" value="{{ old('program_studi') }}">
                @error('program_studi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Select Dosen Wali --}}
        <div class="mb-3">
            <label class="form-label">Dosen Wali</label>
            <select name="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror">
                <option value="">-- Pilih Dosen Wali --</option>
                @foreach($dosens as $dosen)
                    <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                        {{ $dosen->nama }} ({{ $dosen->nip }})
                    </option>
                @endforeach
            </select>
            @error('dosen_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input No HP --}}
        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}">
            @error('no_hp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Checkbox Buat Akun --}}
        <div class="form-check mb-3">
            <input type="checkbox" name="buat_akun_login" value="1" class="form-check-input @error('buat_akun_login') is-invalid @enderror" id="buatAkun" {{ old('buat_akun_login') ? 'checked' : '' }}>
            <label class="form-check-label" for="buatAkun">Buatkan akun login untuk mahasiswa ini (butuh email di atas)</label>
            @error('buat_akun_login')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input Password --}}
        <div class="mb-3">
            <label class="form-label">Password Akun (opsional, default: password123)</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection