@extends('layouts.app')
@section('page-title', 'Dashboard Mahasiswa')
@section('content')
<div class="card p-4 mb-3">
    <h5>Selamat datang, {{ auth()->user()->name }}</h5>
    @if($mahasiswa)
        <table class="table table-borderless mb-0">
            <tr><th style="width:180px">NIM</th><td>{{ $mahasiswa->nim }}</td></tr>
            <tr><th>Program Studi</th><td>{{ $mahasiswa->program_studi ?? '-' }}</td></tr>
            <tr><th>Angkatan</th><td>{{ $mahasiswa->angkatan ?? '-' }}</td></tr>
            <tr>
                <th>Dosen Wali</th>
                <td>
                    @if($mahasiswa->dosen)
                        {{ $mahasiswa->dosen->nama }} ({{ $mahasiswa->dosen->nip }})
                    @else
                        <span class="badge bg-warning text-dark">Belum ditentukan admin</span>
                    @endif
                </td>
            </tr>
            <tr><th>Total Perwalian Tercatat</th><td>{{ $totalPerwalian }}</td></tr>
        </table>
    @else
        <div class="alert alert-warning mb-0">Akun Anda belum terhubung dengan data mahasiswa. Silakan hubungi admin.</div>
    @endif
</div>

<a href="{{ route('mahasiswa.perwalian.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Perwalian Baru</a>
<a href="{{ route('mahasiswa.perwalian.index') }}" class="btn btn-outline-secondary">Lihat Riwayat Perwalian</a>
@endsection
