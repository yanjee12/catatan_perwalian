@extends('layouts.app')
@section('page-title', 'Histori Perwalian Mahasiswa Wali')

@section('content')
<!-- Filter Form -->
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="q" class="form-control" placeholder="Cari NIM / nama mahasiswa" value="{{ request('q') }}">
    </div>
    <div class="col-md-2">
        <select name="semester" class="form-select">
            <option value="">Semua Semester</option>
            <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
            <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="tahun_ajaran" class="form-control" placeholder="Tahun Ajaran" value="{{ request('tahun_ajaran') }}">
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100"><i class="bi bi-filter me-1"></i> Filter</button>
    </div>
</form>

<!-- Notification Alert -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card p-3 border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Tanggal</th>
                    <th>SKS</th>
                    <th>IPK</th>
                    <th>Catatan Mahasiswa</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perwalians as $p)
                    <tr>
                        <td class="font-monospace fw-bold">{{ $p->mahasiswa->nim ?? '-' }}</td>
                        <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                        <td>{{ $p->semester ?? '-' }}</td>
                        <td>{{ $p->tahun_ajaran ?? '-' }}</td>
                        <td>
                            {{ is_string($p->tanggal_perwalian) ? \Carbon\Carbon::parse($p->tanggal_perwalian)->format('d-m-Y') : $p->tanggal_perwalian->format('d-m-Y') }}
                        </td>
                        <td>{{ $p->sks_diambil ?? '-' }}</td>
                        <td>{{ $p->ipk ?? '-' }}</td>
                        <td>{{ Str::limit($p->catatan ?? '-', 40) }}</td>
                        
                        <!-- BADGE STATUS -->
                        <td class="text-center">
                            @switch($p->status)
                                @case('diajukan')
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">
                                        <i class="bi bi-hourglass-split me-1"></i> Diajukan
                                    </span>
                                    @break

                                @case('berlangsung')
                                    <span class="badge bg-info text-white px-2 py-1 rounded-pill">
                                        <i class="bi bi-arrow-repeat me-1"></i> Berlangsung
                                    </span>
                                    @break

                                @case('disetujui')
                                    <span class="badge bg-success px-2 py-1 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                    @break

                                @case('ditolak')
                                    <span class="badge bg-danger px-2 py-1 rounded-pill" title="{{ $p->catatan_dosen ?? '' }}">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                    @break

                                @default
                                    <span class="badge bg-secondary px-2 py-1 rounded-pill">
                                        {{ ucfirst($p->status ?? 'Pending') }}
                                    </span>
                            @endswitch
                        </td>

                        <!-- TOMBOL PROSES DOSEN -->
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-2" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $p->id }}" title="Ubah Status">
                                <i class="bi bi-pencil-square me-1"></i> Proses
                            </button>
                        </td>
                    </tr>

                    <!-- MODAL UBAH STATUS DOSEN -->
                    <div class="modal fade" id="modalStatus{{ $p->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('dosen.perwalian.updateStatus', $p->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Update Status Perwalian</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <p class="mb-1"><strong>Mahasiswa:</strong> {{ $p->mahasiswa->nama ?? 'N/A' }}</p>
                                        <p class="mb-3"><strong>NIM:</strong> {{ $p->mahasiswa->nim ?? '-' }}</p>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status Perwalian</label>
                                            <select name="status" class="form-select" required>
                                                <option value="diajukan" {{ $p->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                                <option value="berlangsung" {{ $p->status == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                                <option value="disetujui" {{ $p->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Catatan Dosen Wali</label>
                                            <textarea name="catatan_dosen" class="form-control" rows="3" placeholder="Masukkan catatan atau alasan jika revisi/ditolak...">{{ $p->catatan_dosen }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada data perwalian dari mahasiswa wali Anda.
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