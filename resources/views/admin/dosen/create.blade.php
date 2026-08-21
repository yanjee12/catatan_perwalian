@extends('layouts.app')
@section('page-title', 'Tambah Dosen Wali')
@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('admin.dosen.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">NIP</label>
            <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="buat_akun_login" value="1" class="form-check-input" id="buatAkun">
            <label class="form-check-label" for="buatAkun">Buatkan akun login untuk dosen ini (butuh email di atas)</label>
        </div>
        <div class="mb-3">
            <label class="form-label">Password Akun (opsional, default: password123)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.dosen.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection
