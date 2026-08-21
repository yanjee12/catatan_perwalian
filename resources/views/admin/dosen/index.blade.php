@extends('layouts.app')
@section('page-title', 'Pengelolaan Dosen Wali')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="q" class="form-control" placeholder="Cari nama / NIP" value="{{ request('q') }}">
        <button class="btn btn-outline-secondary">Cari</button>
    </form>
    <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Dosen Wali</a>
</div>

<div class="card p-3">
    <table class="table table-hover align-middle">
        <thead>
            <tr><th>NIP</th><th>Nama</th><th>Email</th><th>No HP</th><th>Jml Mahasiswa Wali</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($dosens as $dosen)
                <tr>
                    <td>{{ $dosen->nip }}</td>
                    <td>{{ $dosen->nama }}</td>
                    <td>{{ $dosen->email ?? '-' }}</td>
                    <td>{{ $dosen->no_hp ?? '-' }}</td>
                    <td>{{ $dosen->mahasiswas_count }}</td>
                    <td>
                        <a href="{{ route('admin.dosen.edit', $dosen) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data dosen ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data dosen wali.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $dosens->links() }}
</div>
@endsection
