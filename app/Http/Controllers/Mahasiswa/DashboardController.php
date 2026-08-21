<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa()->with('dosen')->first();
        $totalPerwalian = $mahasiswa ? $mahasiswa->perwalians()->count() : 0;

        return view('mahasiswa.dashboard', compact('mahasiswa', 'totalPerwalian'));
    }
}
