@extends('layouts.app')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card p-3 shadow-sm h-100">
            <div class="text-muted small">Total Mahasiswa</div>
            <div class="fs-3 fw-bold">{{ $totalMahasiswa }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card p-3 shadow-sm h-100">
            <div class="text-muted small">Total Dosen Wali</div>
            <div class="fs-3 fw-bold">{{ $totalDosen }}</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card p-3 shadow-sm h-100">
            <div class="text-muted small">Total Perwalian</div>
            <div class="fs-3 fw-bold">{{ $totalPerwalian }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 shadow-sm h-100">
            <div class="text-muted small">Bimbingan Perlu Persetujuan</div>
            <div class="fs-3 fw-bold text-warning">{{ $bimbinganDiajukan ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 shadow-sm h-100">
            <div class="text-muted small">Mahasiswa Belum Ada Dosen</div>
            <div class="fs-3 fw-bold text-danger">{{ $mahasiswaBelumPunyaDosen }}</div>
        </div>
    </div>
</div>

<div class="card p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="m-0 fw-bold">Perwalian Terbaru</h6>
        <a href="{{ route('admin.bimbingan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Bimbingan</a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Dosen Wali</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Tanggal Jadwal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perwalianTerbaru as $p)
                    <tr>
                        <td>{{ $p->mahasiswa->nim ?? '-' }}</td>
                        <td>{{ $p->mahasiswa->nama ?? $p->mahasiswa->name ?? '-' }}</td>
                        <td>{{ $p->dosen->nama ?? $p->dosen->name ?? '-' }}</td>
                        <td>{{ $p->semester }}</td>
                        <td>{{ $p->tahun_ajaran }}</td>
                        <td>{{ $p->tanggal_jadwal ? $p->tanggal_jadwal->format('d-m-Y H:i') : ($p->tanggal_perwalian ? $p->tanggal_perwalian->format('d-m-Y') : '-') }}</td>
                        <td>
                            @switch($p->status ?? 'diajukan')
                                @case('diajukan')
                                    <span class="badge bg-warning text-dark">Sudah Diajukan</span>
                                    @break
                                @case('berlangsung')
                                    <span class="badge bg-primary">Sedang Berlangsung</span>
                                    @break
                                @case('disetujui')
                                    <span class="badge bg-success">Sudah Disetujui</span>
                                    @break
                                @case('ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">-</span>
                            @endswitch
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">Belum ada data perwalian terbaru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection