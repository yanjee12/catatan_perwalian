@extends('layouts.app')
@section('page-title', 'Kelola Data Mahasiswa')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <div></div>
    <!-- Tombol Tambah Mahasiswa jika diperlukan -->
    @if(Route::has('admin.mahasiswa.create'))
        <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Mahasiswa
        </a>
    @endif
</div>

<div class="card p-3 border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th class="text-center">Total SKS</th>
                    <th class="text-center">IPK</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $index => $m)
                    <tr>
                        <td>{{ $mahasiswas->firstItem() + $index }}</td>
                        <td><strong>{{ $m->nim ?? '-' }}</strong></td>
                        <td>{{ $m->nama ?? '-' }}</td>
                        
                        <!-- Panggil via relasi perwalianTerbaru -->
                        <td>{{ $m->perwalianTerbaru->semester ?? '-' }}</td>
                        <td>{{ $m->perwalianTerbaru->tahun_ajaran ?? '-' }}</td>
                        
                        <!-- Menampilkan Rekap Total SKS dari Controller (total_sks) atau fallback ke sks_diambil -->
                        <td class="text-center fw-bold text-primary">
                            {{ $m->total_sks ?? $m->sks_diambil ?? 0 }} SKS
                        </td>

                        <!-- IPK mengambil dari perwalianTerbaru, jika kosong panggil $m->ipk -->
                        <td class="text-center fw-semibold">
                            @php
                                $ipkVal = $m->perwalianTerbaru->ipk ?? $m->ipk ?? null;
                            @endphp
                            {{ $ipkVal !== null ? number_format((float)$ipkVal, 2) : '-' }}
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                {{-- Tombol Edit --}}
                                @if(Route::has('admin.mahasiswa.edit'))
                                    <a href="{{ route('admin.mahasiswa.edit', $m->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                @endif

                                {{-- Tombol Hapus --}}
                                @if(Route::has('admin.mahasiswa.destroy'))
                                    <form action="{{ route('admin.mahasiswa.destroy', $m->id) }}" method="POST" class="form-delete-{{ $m->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('{{ $m->id }}', '{{ $m->nama }}')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                            Belum ada data mahasiswa.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $mahasiswas->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus mahasiswa ${nama} beserta akun loginnya?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(`.form-delete-${id}`).submit();
            }
        });
    }
</script>
@endpush