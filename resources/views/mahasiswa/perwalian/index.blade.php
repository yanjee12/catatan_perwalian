@extends('layouts.app')
@section('page-title', 'Riwayat Perwalian Saya')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <div></div>
    <a href="{{ route('mahasiswa.perwalian.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Catat Perwalian Baru
    </a>
</div>

<div class="card p-3 border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Dosen Wali</th>
                    <th>SKS</th>
                    <th>IPK</th>
                    <th>Catatan</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perwalians as $p)
                    <tr>
                        <td>
                            {{ is_string($p->tanggal_perwalian) ? \Carbon\Carbon::parse($p->tanggal_perwalian)->format('d-m-Y') : $p->tanggal_perwalian->format('d-m-Y') }}
                        </td>
                        <td>{{ $p->semester ?? '-' }}</td>
                        <td>{{ $p->tahun_ajaran ?? '-' }}</td>
                        <td>{{ $p->dosen->nama ?? '-' }}</td>
                        <td>{{ $p->sks_diambil ?? '-' }}</td>
                        <td>{{ $p->ipk ?? '-' }}</td>
                        <td>{{ Str::limit($p->catatan ?? '-', 40) }}</td>
                        
                        <!-- TAMPILAN BADGE STATUS DENGAN IKON -->
                        <td class="text-center">
                            @switch($p->status)
                                @case('diajukan')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        <i class="bi bi-hourglass-split me-1"></i> Diajukan
                                    </span>
                                    @break

                                @case('berlangsung')
                                    <span class="badge bg-info text-white px-3 py-2 rounded-pill">
                                        <i class="bi bi-arrow-repeat me-1"></i> Berlangsung
                                    </span>
                                    @break

                                @case('disetujui')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                    @break

                                @case('ditolak')
                                    <span class="badge bg-danger px-3 py-2 rounded-pill" title="{{ $p->catatan_dosen ?? '' }}">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                    @break

                                @default
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                        <i class="bi bi-question-circle me-1"></i> {{ ucfirst($p->status ?? 'Pending') }}
                                    </span>
                            @endswitch
                        </td>
                    </tr>
                @empty
                    <tr>
                        <!-- DIBUAT COLSPAN 8 AGAR RAPI SAAT KOSONG -->
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                            Anda belum pernah mencatat perwalian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $perwalians->links() }}
    </div>
</div>
@endsection