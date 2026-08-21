@extends('layouts.app')

@section('title', 'Kelola Status Bimbingan / Perwalian')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold m-0 text-primary">Status Bimbingan Perwalian</h4>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">
            {{-- Form Filter Status & Pencarian --}}
            <form action="{{ route('admin.bimbingan.index') }}" method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari Nama/NIM/Dosen..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </div>
            </form>

            {{-- Tabel Data --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle border-top">
                    <thead class="table-light">
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Dosen Wali</th>
                            <th>Tanggal</th>
                            <th>Status Saat Ini</th>
                            <th>Catatan Dosen / Admin</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bimbingans as $b)
                            <tr>
                                <td>
                                    <strong>{{ $b->mahasiswa?->nama ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ $b->mahasiswa?->nim ?? '-' }}</small>
                                </td>
                                <td>{{ $b->dosen?->nama ?? '-' }}</td>
                                <td>{{ $b->tanggal_perwalian ? \Carbon\Carbon::parse($b->tanggal_perwalian)->format('d-m-Y H:i') : '-' }}</td>
                                
                                {{-- Pilihan Warna Badge Sesuai Status yang Set oleh Admin --}}
                                <td>
                                    @switch(strtolower($b->status))
                                        @case('diajukan')
                                            <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-clock me-1"></i>Diajukan</span>
                                            @break
                                        @case('berlangsung')
                                            <span class="badge bg-info text-dark px-2 py-1"><i class="bi bi-arrow-repeat me-1"></i>Berlangsung</span>
                                            @break
                                        @case('disetujui')
                                            <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                            @break
                                        @case('ditolak')
                                            <span class="badge bg-danger px-2 py-1"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary px-2 py-1">{{ $b->status ?? '-' }}</span>
                                    @endswitch
                                </td>

                                <td>
                                    <small class="text-secondary">{{ Str::limit($b->catatan_dosen ?? '-', 40) }}</small>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary fw-bold" data-bs-toggle="modal" data-bs-target="#editModal{{ $b->id }}">
                                        Ubah Status
                                    </button>
                                </td>
                            </tr>

                            {{-- Modal Update Status --}}
                            <div class="modal fade" id="editModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.bimbingan.update-status', $b->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title fs-6 fw-bold">Set Status Perwalian</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label small text-muted fw-bold">Mahasiswa</label>
                                                    <input type="text" class="form-control form-control-sm" value="{{ $b->mahasiswa?->nama ?? '-' }}" disabled>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small text-muted fw-bold">Ubah Status Ke:</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="diajukan" {{ $b->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                                        <option value="berlangsung" {{ $b->status == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                                        <option value="disetujui" {{ $b->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                        <option value="ditolak" {{ $b->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small text-muted fw-bold">Catatan Admin / Dosen</label>
                                                    <textarea name="catatan_dosen" class="form-control" rows="3" placeholder="Masukkan catatan tambahan jika ada...">{{ $b->catatan_dosen }}</textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-primary fw-bold">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data bimbingan perwalian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bimbingans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection