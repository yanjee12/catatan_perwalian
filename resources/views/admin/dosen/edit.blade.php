@extends('layouts.app')
@section('page-title', 'Edit Dosen Wali')
@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('admin.dosen.update', $dosen) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">NIP</label>
            <input type="text" name="nip" class="form-control" value="{{ old('nip', $dosen->nip) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $dosen->nama) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $dosen->email) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $dosen->no_hp) }}">
        </div>
        <button class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.dosen.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection
