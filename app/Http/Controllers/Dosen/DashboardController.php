<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;
        $totalMahasiswaWali = $dosen ? $dosen->mahasiswas()->count() : 0;
        $totalPerwalian = $dosen ? $dosen->perwalians()->count() : 0;

        return view('dosen.dashboard', compact('dosen', 'totalMahasiswaWali', 'totalPerwalian'));
    }
}
