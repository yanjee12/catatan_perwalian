<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswas = Mahasiswa::with(['dosen', 'user', 'perwalianTerbaru'])
            // Menjumlahkan total SKS dari relasi perwalians (mengisi properti $m->total_sks)
            ->withSum('perwalians as total_sks', 'sks_diambil')
            ->when($request->q, function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->q}%")
                  ->orWhere('nim', 'like', "%{$request->q}%")
                  ->orWhere('email', 'like', "%{$request->q}%");
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('admin.mahasiswa.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim'             => 'required|string|unique:mahasiswas,nim',
            'nama'            => 'required|string|max:255',
            'email'           => 'nullable|email|unique:users,email|unique:mahasiswas,email',
            'no_hp'           => 'nullable|string|max:20',
            'dosen_id'        => 'nullable|exists:dosens,id',
            'angkatan'        => 'nullable|string|max:4',
            'program_studi'   => 'nullable|string|max:100',
            'ipk'             => 'nullable|numeric|between:0,4.00',
            'sks_diambil'     => 'nullable|integer|min:0',
            'catatan'         => 'nullable|string',
            'buat_akun_login' => 'nullable|boolean',
            'password'        => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($data, $request) {
            $userId = null;

            if ($request->boolean('buat_akun_login') && !empty($data['email'])) {
                $user = User::create([
                    'name'     => $data['nama'],
                    'email'    => $data['email'],
                    'password' => Hash::make($data['password'] ?: 'password123'),
                    'role'     => 'mahasiswa',
                ]);
                $userId = $user->id;
            }

            Mahasiswa::create([
                'user_id'       => $userId,
                'dosen_id'      => $data['dosen_id'] ?? null,
                'nim'           => $data['nim'],
                'nama'          => $data['nama'],
                'email'         => $data['email'] ?? null,
                'no_hp'         => $data['no_hp'] ?? null,
                'angkatan'      => $data['angkatan'] ?? null,
                'program_studi' => $data['program_studi'] ?? null,
                'ipk'           => filled($data['ipk'] ?? null) ? (float) $data['ipk'] : null,
                'sks_diambil'   => filled($data['sks_diambil'] ?? null) ? (int) $data['sks_diambil'] : null,
                'catatan'       => $data['catatan'] ?? null,
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'dosens'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $userId = $mahasiswa->user_id;

        $emailRule = ['nullable', 'email', 'unique:mahasiswas,email,' . $mahasiswa->id];
        if ($userId) {
            $emailRule[] = 'unique:users,email,' . $userId;
        } else {
            $emailRule[] = 'unique:users,email';
        }

        $data = $request->validate([
            'nim'           => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama'          => 'required|string|max:255',
            'email'         => $emailRule,
            'no_hp'         => 'nullable|string|max:20',
            'dosen_id'      => 'nullable|exists:dosens,id',
            'angkatan'      => 'nullable|string|max:4',
            'program_studi' => 'nullable|string|max:100',
            'ipk'           => 'nullable|numeric|between:0,4.00',
            'sks_diambil'   => 'nullable|integer|min:0',
            'catatan'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($mahasiswa, $data) {
            $ipk = filled($data['ipk'] ?? null) ? (float) $data['ipk'] : null;
            $sks = filled($data['sks_diambil'] ?? null) ? (int) $data['sks_diambil'] : null;

            $mahasiswa->update([
                'nim'           => $data['nim'],
                'nama'          => $data['nama'],
                'email'         => $data['email'] ?? null,
                'no_hp'         => $data['no_hp'] ?? null,
                'dosen_id'      => $data['dosen_id'] ?? null,
                'angkatan'      => $data['angkatan'] ?? null,
                'program_studi' => $data['program_studi'] ?? null,
                'ipk'           => $ipk,
                'sks_diambil'   => $sks,
                'catatan'       => $data['catatan'] ?? null,
            ]);

            if ($mahasiswa->user_id) {
                $user = User::find($mahasiswa->user_id);
                if ($user) {
                    $userData = ['name' => $data['nama']];
                    if (!empty($data['email'])) {
                        $userData['email'] = $data['email'];
                    }
                    $user->update($userData);
                }
            }
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        DB::transaction(function () use ($mahasiswa) {
            $user = $mahasiswa->user;

            $mahasiswa->delete();

            if ($user) {
                $user->delete();
            }
        });

        return back()->with('success', 'Data mahasiswa dan akun terkait berhasil dihapus.');
    }
}