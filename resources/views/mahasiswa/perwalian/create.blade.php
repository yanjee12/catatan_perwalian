@extends('layouts.app')
@section('page-title', 'Catat Perwalian')
@section('content')
<div class="card p-4" style="max-width:650px;">
    <div class="alert alert-secondary">
        Dosen Wali: <strong>{{ $mahasiswa->dosen->nama }}</strong> ({{ $mahasiswa->dosen->nip }})
        <div class="small text-muted">Dosen wali otomatis mengikuti data yang telah ditentukan oleh admin.</div>
    </div>
    <form method="POST" action="{{ route('mahasiswa.perwalian.store') }}">
        @csrf
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                    <option value="">-- Pilih --</option>
                    <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col mb-3">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror" placeholder="contoh: 2024/2025" value="{{ old('tahun_ajaran') }}" required>
                @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Perwalian</label>
            <input type="date" name="tanggal_perwalian" class="form-control @error('tanggal_perwalian') is-invalid @enderror" value="{{ old('tanggal_perwalian', date('Y-m-d')) }}" required>
            @error('tanggal_perwalian') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">SKS Diambil</label>
                <input type="number" name="sks_diambil" min="0" max="30" class="form-control @error('sks_diambil') is-invalid @enderror" value="{{ old('sks_diambil') }}" placeholder="contoh: 24">
                @error('sks_diambil') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col mb-3">
                <label class="form-label">IPK Saat Ini</label>
                <!-- Atribut input desimal presisi -->
                <input 
                    type="number" 
                    step="0.01" 
                    min="0" 
                    max="4.00" 
                    name="ipk" 
                    id="input-ipk"
                    class="form-control @error('ipk') is-invalid @enderror" 
                    value="{{ old('ipk') }}" 
                    placeholder="contoh: 3.50"
                    onblur="if(this.value && !isNaN(this.value)) this.value = parseFloat(this.value).toFixed(2);"
                >
                @error('ipk') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Rencana Studi</label>
            <textarea name="rencana_studi" class="form-control @error('rencana_studi') is-invalid @enderror" rows="3">{{ old('rencana_studi') }}</textarea>
            @error('rencana_studi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Catatan Perwalian</label>
            <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" placeholder="Hasil diskusi, arahan dosen wali, dsb.">{{ old('catatan') }}</textarea>
            @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary">Simpan Perwalian</button>
        <a href="{{ route('mahasiswa.perwalian.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>
@endsection