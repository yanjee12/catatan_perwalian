<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan; // 1. Tambahkan import Model Bimbingan
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Perwalian;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalPerwalian = Perwalian::count();
        $mahasiswaBelumPunyaDosen = Mahasiswa::whereNull('dosen_id')->count();
        $bimbinganDiajukan = 0;

        $perwalianTerbaru = Perwalian::with(['mahasiswa', 'dosen'])
            ->orderByDesc('tanggal_perwalian')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalMahasiswa', 
            'totalDosen', 
            'totalPerwalian', 
            'mahasiswaBelumPunyaDosen', 
            'perwalianTerbaru',
            'bimbinganDiajukan' // 3. Masukkan ke dalam compact
            
        ));
    }
}