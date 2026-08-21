@extends('layouts.app')
@section('page-title', 'Dashboard Dosen')
@section('content')
<div class="card p-4 mb-3">
    <h5>Selamat datang, {{ auth()->user()->name }}</h5>
    @if($dosen)
        <table class="table table-borderless mb-0">
            <tr><th style="width:220px">NIP</th><td>{{ $dosen->nip }}</td></tr>
            <tr><th>Jumlah Mahasiswa Wali</th><td>{{ $totalMahasiswaWali }}</td></tr>
            <tr><th>Total Perwalian Tercatat</th><td>{{ $totalPerwalian }}</td></tr>
        </table>
    @else
        <div class="alert alert-warning mb-0">Akun Anda belum terhubung dengan data dosen. Silakan hubungi admin.</div>
    @endif
</div>

<a href="{{ route('dosen.perwalian.index') }}" class="btn btn-primary"><i class="bi bi-journal-text"></i> Lihat Histori Perwalian Mahasiswa Wali</a>
@endsection
