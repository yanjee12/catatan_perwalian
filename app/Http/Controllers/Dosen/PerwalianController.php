<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Perwalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerwalianController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            return redirect()->route('dosen.dashboard')
                ->with('error', 'Akun Anda belum terhubung dengan data dosen. Hubungi admin.');
        }

        $perwalians = $dosen->perwalians()
            ->with('mahasiswa')
            ->when($request->q, function ($q) use ($request) {
                $q->whereHas('mahasiswa', function ($sub) use ($request) {
                    $sub->where('nama', 'like', "%{$request->q}%")
                        ->orWhere('nim', 'like', "%{$request->q}%");
                });
            })
            ->when($request->semester, fn ($q) => $q->where('semester', $request->semester))
            ->when($request->status, fn ($q) => $q->where('status', $request->status)) // TAMBAHAN FILTER STATUS
            ->when($request->tahun_ajaran, fn ($q) => $q->where('tahun_ajaran', 'like', "%{$request->tahun_ajaran}%"))
            ->orderByDesc('tanggal_perwalian')
            ->paginate(15)
            ->withQueryString();

        return view('dosen.perwalian.index', compact('dosen', 'perwalians'));
    }

    // METHOD BARU UNTUK UPDATE STATUS PERWALIAN
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:diajukan,berlangsung,disetujui,ditolak',
            'catatan_dosen' => 'nullable|string|max:1000',
        ]);

        $dosen = Auth::user()->dosen;

        // Memastikan perwalian tersebut benar milik mahasiswa bimbingan dosen yang login
        $perwalian = Perwalian::where('id', $id)
            ->where('dosen_id', $dosen->id)
            ->firstOrFail();

        $perwalian->update([
            'status'        => $request->status,
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return redirect()->back()->with('success', 'Status perwalian berhasil diperbarui.');
    }
}