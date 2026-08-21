@extends('layouts.app')
@section('page-title', 'Import Data Mahasiswa & Dosen')
@section('content')

@if(session('import_errors') && count(session('import_errors')))
    <div class="alert alert-warning">
        <strong>Catatan import:</strong>
        <ul class="mb-0">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <div class="card p-4">
            <h6>Import Data Dosen Wali</h6>
            <p class="text-muted small">Format kolom CSV (baris pertama header): <code>nip,nama,email,no_hp</code></p>
            <a href="{{ route('admin.import.index') }}#" class="small">Contoh: lihat file <code>templates/dosen_template.csv</code> yang disertakan.</a>
            <form method="POST" action="{{ route('admin.import.dosen') }}" enctype="multipart/form-data" class="mt-3">
                @csrf
                <input type="file" name="file_dosen" accept=".csv,.txt" class="form-control mb-3" required>
                <button class="btn btn-primary">Import Dosen</button>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-4">
            <h6>Import Data Mahasiswa</h6>
            <p class="text-muted small">Format kolom CSV (baris pertama header): <code>nim,nama,angkatan,program_studi,nip_dosen_wali,email,no_hp</code></p>
            <p class="text-muted small">Kolom <code>nip_dosen_wali</code> bersifat opsional; isi jika ingin langsung menautkan mahasiswa ke dosen wali yang sudah ada.</p>
            <form method="POST" action="{{ route('admin.import.mahasiswa') }}" enctype="multipart/form-data" class="mt-3">
                @csrf
                <input type="file" name="file_mahasiswa" accept=".csv,.txt" class="form-control mb-3" required>
                <button class="btn btn-primary">Import Mahasiswa</button>
            </form>
        </div>
    </div>
</div>

<div class="alert alert-info mt-3">
    Data yang di-import akan otomatis diperbarui (upsert) berdasarkan NIP untuk dosen dan NIM untuk mahasiswa jika data sudah ada sebelumnya.
</div>
@endsection
