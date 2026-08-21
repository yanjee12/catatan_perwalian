<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perwalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminBimbinganController extends Controller
{
    public function index(Request $request)
    {
        $query = Perwalian::with(['mahasiswa', 'dosen']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($qMhs) use ($search) {
                    $qMhs->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                })->orWhereHas('dosen', function ($qDsn) use ($search) {
                    $qDsn->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $bimbingans = $query->orderBy('tanggal_perwalian', 'desc')
                            ->paginate(10)
                            ->withQueryString();

        return view('admin.bimbingan.index', compact('bimbingans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:diajukan,berlangsung,disetujui,ditolak',
            'catatan_dosen' => 'nullable|string|max:1000',
        ]);

        try {
            $perwalian = Perwalian::findOrFail($id);

            $perwalian->status = $request->status;
            
            $catatanBaru = $request->catatan_dosen;
            if ($catatanBaru && !str_starts_with($catatanBaru, '[Diubah oleh Admin]')) {
                $catatanBaru = '[Diubah oleh Admin] ' . $catatanBaru;
            }
            $perwalian->catatan_dosen = $catatanBaru;

            $perwalian->save();

            return redirect()->back()->with('success', 'Status perwalian berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal update status perwalian: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $perwalian = Perwalian::findOrFail($id);
            $perwalian->delete();

            return redirect()->back()->with('success', 'Data perwalian berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus perwalian: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data perwalian.');
        }
    }
}