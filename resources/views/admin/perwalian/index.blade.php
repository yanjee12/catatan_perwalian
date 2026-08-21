@extends('layouts.app')
@section('page-title', 'Rekap Data Perwalian')

@section('content')
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="q" class="form-control" placeholder="Cari NIM / nama mahasiswa" value="{{ request('q') }}">
    </div>
    <div class="col-md-2">
        <select name="dosen_id" class="form-select">
            <option value="">Semua Dosen Wali</option>
            @foreach($dosens as $dosen)
                <option value="{{ $dosen->id }}" {{ request('dosen_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="semester" class="form-select">
            <option value="">Semua Semester</option>
            <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="text" name="tahun_ajaran" class="form-control" placeholder="Tahun Ajaran" value="{{ request('tahun_ajaran') }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="dari_tanggal" class="form-control" value="{{ request('dari_tanggal') }}">
    </div>
    <div class="col-md-1">
        <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
    </div>
</form>

<div class="card p-3 border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Dosen Wali</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Tanggal</th>
                    <th class="text-center">SKS</th>
                    <th class="text-center">IPK</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perwalians as $p)
                    <tr>
                        <td><strong>{{ $p->mahasiswa->nim ?? '-' }}</strong></td>
                        <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                        <td>{{ $p->dosen->nama ?? '-' }}</td>
                        <td>{{ $p->semester }}</td>
                        <td>{{ $p->tahun_ajaran }}</td>
                        <td>{{ is_string($p->tanggal_perwalian) ? \Carbon\Carbon::parse($p->tanggal_perwalian)->format('d-m-Y') : $p->tanggal_perwalian->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $p->sks_diambil ?? '-' }}</td>
                        <td class="text-center fw-bold">{{ isset($p->ipk) ? number_format((float)$p->ipk, 2) : '-' }}</td>
                        
                        <!-- Status Badge -->
                        <td class="text-center">
                            @php
                                $status = strtolower($p->status ?? 'pending');
                            @endphp
                            @if($status === 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @elseif($status === 'disetujui' || $status === 'acc')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>

                        <!-- Tombol Hapus -->
                        <td class="text-center">
                            <form action="{{ route('admin.perwalian.destroy', $p->id) }}" method="POST" class="form-delete-{{ $p->id }} d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ $p->id }}')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                            Belum ada data perwalian.
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

<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data perwalian ini?')) {
            document.querySelector(`.form-delete-${id}`).submit();
        }
    }
</script>
@endsection