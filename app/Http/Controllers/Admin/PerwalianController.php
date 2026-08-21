<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Perwalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerwalianController extends Controller
{
    public function index(Request $request)
    {
        // Tampilkan seluruh data perwalian termasuk yang berstatus ditolak
        $perwalians = Perwalian::with(['mahasiswa', 'dosen'])
            ->when($request->dosen_id, fn ($q) => $q->where('dosen_id', $request->dosen_id))
            ->when($request->semester, fn ($q) => $q->where('semester', $request->semester))
            ->when($request->tahun_ajaran, fn ($q) => $q->where('tahun_ajaran', 'like', "%{$request->tahun_ajaran}%"))
            ->when($request->q, function ($q) use ($request) {
                $q->whereHas('mahasiswa', function ($sub) use ($request) {
                    $sub->where('nama', 'like', "%{$request->q}%")
                        ->orWhere('nim', 'like', "%{$request->q}%");
                });
            })
            ->when($request->dari_tanggal, fn ($q) => $q->whereDate('tanggal_perwalian', '>=', $request->dari_tanggal))
            ->when($request->sampai_tanggal, fn ($q) => $q->whereDate('tanggal_perwalian', '<=', $request->sampai_tanggal))
            ->orderByDesc('tanggal_perwalian')
            ->paginate(20)
            ->withQueryString();

        $dosens = Dosen::orderBy('nama')->get();

        return view('admin.perwalian.index', compact('perwalians', 'dosens'));
    }

    public function edit(Perwalian $perwalian)
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('admin.perwalian.edit', compact('perwalian', 'dosens'));
    }

    public function update(Request $request, Perwalian $perwalian)
    {
        $data = $request->validate([
            'status'            => 'nullable|string|in:pending,disetujui,ditolak',
            'semester'          => 'nullable|string|max:10',
            'tahun_ajaran'      => 'nullable|string|max:20',
            'tanggal_perwalian' => 'nullable|date',
            'ipk'               => 'nullable|numeric|between:0,4.00',
            'sks_diambil'       => 'nullable|integer|min:0',
            'catatan'           => 'nullable|string',
        ]);

        DB::transaction(function () use ($perwalian, $data) {
            $ipk = filled($data['ipk'] ?? null) ? (float) $data['ipk'] : null;
            $sks = filled($data['sks_diambil'] ?? null) ? (int) $data['sks_diambil'] : null;

            // 1. Update Riwayat Perwalian
            $perwalian->update([
                'status'            => $data['status'] ?? $perwalian->status,
                'semester'          => $data['semester'] ?? $perwalian->semester,
                'tahun_ajaran'      => $data['tahun_ajaran'] ?? $perwalian->tahun_ajaran,
                'tanggal_perwalian' => $data['tanggal_perwalian'] ?? $perwalian->tanggal_perwalian,
                'ipk'               => $ipk ?? $perwalian->ipk,
                'sks_diambil'       => $sks ?? $perwalian->sks_diambil,
                'catatan'           => $data['catatan'] ?? $perwalian->catatan,
            ]);

            // 2. Sync otomatis ke tabel Master Mahasiswa
            if ($perwalian->mahasiswa_id) {
                $mahasiswa = Mahasiswa::find($perwalian->mahasiswa_id);
                if ($mahasiswa) {
                    $mahasiswa->update([
                        'ipk'         => $ipk ?? $mahasiswa->ipk,
                        'sks_diambil' => $sks ?? $mahasiswa->sks_diambil,
                        'catatan'     => $data['catatan'] ?? $mahasiswa->catatan,
                    ]);
                }
            }
        });

        return redirect()->route('admin.perwalian.index')->with('success', 'Data perwalian dan IPK mahasiswa berhasil diperbarui.');
    }

    public function destroy(Perwalian $perwalian)
    {
        $perwalian->delete();
        return back()->with('success', 'Data perwalian berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $perwalians = Perwalian::with(['mahasiswa', 'dosen'])
            ->when($request->dosen_id, fn ($q) => $q->where('dosen_id', $request->dosen_id))
            ->when($request->semester, fn ($q) => $q->where('semester', $request->semester))
            ->when($request->tahun_ajaran, fn ($q) => $q->where('tahun_ajaran', 'like', "%{$request->tahun_ajaran}%"))
            ->orderByDesc('tanggal_perwalian')
            ->get();

        $filename = 'rekap-perwalian-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($perwalians) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['NIM', 'Nama Mahasiswa', 'Dosen Wali', 'Semester', 'Tahun Ajaran', 'Tanggal Perwalian', 'SKS Diambil', 'IPK', 'Status', 'Catatan']);

            foreach ($perwalians as $p) {
                fputcsv($handle, [
                    $p->mahasiswa->nim ?? '-',
                    $p->mahasiswa->nama ?? '-',
                    $p->dosen->nama ?? '-',
                    $p->semester,
                    $p->tahun_ajaran,
                    optional($p->tanggal_perwalian)->format('Y-m-d'),
                    $p->sks_diambil ?? $p->mahasiswa->sks_diambil ?? '-',
                    $p->ipk ?? $p->mahasiswa->ipk ?? '-',
                    $p->status ?? 'pending',
                    $p->catatan ?? $p->mahasiswa->catatan ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}