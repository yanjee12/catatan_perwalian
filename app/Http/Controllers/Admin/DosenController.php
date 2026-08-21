<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $dosens = Dosen::withCount('mahasiswas')
            ->when($request->q, function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->q}%")
                  ->orWhere('nip', 'like', "%{$request->q}%")
                  ->orWhere('email', 'like', "%{$request->q}%");
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|string|unique:dosens,nip',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email|unique:dosens,email',
            'no_hp' => 'nullable|string|max:20',
            'buat_akun_login' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($data, $request) {
            $userId = null;

            if ($request->boolean('buat_akun_login') && !empty($data['email'])) {
                $user = User::create([
                    'name' => $data['nama'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password'] ?: 'password123'),
                    'role' => 'dosen',
                ]);
                $userId = $user->id;
            }

            Dosen::create([
                'user_id' => $userId,
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'email' => $data['email'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
            ]);
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen wali berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen)
    {
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $userId = $dosen->user_id;

        $data = $request->validate([
            'nip' => 'required|string|unique:dosens,nip,' . $dosen->id,
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:dosens,email,' . $dosen->id . ($userId ? '|unique:users,email,' . $userId : ''),
            'no_hp' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($dosen, $data) {
            // 1. Update data di tabel dosens
            $dosen->update([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'email' => $data['email'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
            ]);

            // 2. Update data di tabel users jika terhubung ke akun login
            if ($dosen->user) {
                $dosen->user->update([
                    'name' => $data['nama'],
                    'email' => $data['email'] ?? $dosen->user->email,
                ]);
            }
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen wali berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        DB::transaction(function () use ($dosen) {
            $user = $dosen->user;
            
            // Hapus data dosen
            $dosen->delete();

            // Hapus akun user terkait jika ada
            if ($user) {
                $user->delete();
            }
        });

        return back()->with('success', 'Data dosen wali dan akun terkait berhasil dihapus.');
    }
}