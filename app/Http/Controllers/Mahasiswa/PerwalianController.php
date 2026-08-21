<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Perwalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerwalianController extends Controller
{
    public function index()
    {
        // Cari data mahasiswa berdasarkan user yang sedang login
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // Ambil riwayat perwalian milik mahasiswa tersebut
        $perwalians = Perwalian::with(['dosen', 'mahasiswa'])
            ->where('mahasiswa_id', $mahasiswa->id ?? null)
            ->orderByDesc('tanggal_perwalian')
            ->paginate(10);

        return view('mahasiswa.perwalian.index', compact('perwalians'));
    }

    public function create()
    {
        $user = Auth::user();

        // Ambil data mahasiswa beserta dosen walinya
        $mahasiswa = Mahasiswa::with('dosen')->where('user_id', $user->id)->firstOrFail();

        // Ambil semua daftar dosen jika pilihan dosen manual diperlukan
        $dosens = Dosen::orderBy('nama')->get();

        // Kirim $mahasiswa dan $dosens ke view
        return view('mahasiswa.perwalian.create', compact('mahasiswa', 'dosens'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'dosen_id'          => 'nullable|exists:dosens,id',
            'tanggal_perwalian' => 'required|date',
            'semester'          => 'nullable|string|max:10',
            'tahun_ajaran'      => 'nullable|string|max:20',
            'sks_diambil'       => 'nullable|integer|min:0',
            'ipk'               => 'nullable|numeric|between:0,4.00',
            'topik'             => 'nullable|string|max:255',
            'catatan'           => 'nullable|string',
        ]);

        Perwalian::create([
            'mahasiswa_id'      => $mahasiswa->id,
            // Jika form tidak mengirim dosen_id, gunakan dosen_id default dari data mahasiswa
            'dosen_id'          => $request->dosen_id ?? $mahasiswa->dosen_id,
            'tanggal_perwalian' => $request->tanggal_perwalian,
            'semester'          => $request->semester ?? $mahasiswa->semester,
            'tahun_ajaran'      => $request->tahun_ajaran ?? $mahasiswa->tahun_ajaran,
            'sks_diambil'       => $request->sks_diambil ?? $mahasiswa->sks_diambil,
            'ipk'               => $request->ipk ?? $mahasiswa->ipk,
            'topik'             => $request->topik,
            'catatan'           => $request->catatan,
            'status'            => 'diajukan',
        ]);

        return redirect()->route('mahasiswa.perwalian.index')->with('success', 'Data perwalian berhasil dicatat.');
    }
}