<?php

use App\Http\Controllers\Admin\AdminBimbinganController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PerwalianController as AdminPerwalianController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\PerwalianController as DosenPerwalianController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\PerwalianController as MahasiswaPerwalianController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'dosen' => redirect()->route('dosen.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => redirect('/login'),
        };
    }
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ================= ADMIN =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('dosen', DosenController::class)->except(['show']);
    Route::resource('mahasiswa', MahasiswaController::class)->except(['show']);

    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::post('/import/dosen', [ImportController::class, 'importDosen'])->name('import.dosen');
    Route::post('/import/mahasiswa', [ImportController::class, 'importMahasiswa'])->name('import.mahasiswa');

    // Route Perwalian Admin
    Route::get('/perwalian', [AdminPerwalianController::class, 'index'])->name('perwalian.index');
    Route::get('/perwalian/export', [AdminPerwalianController::class, 'export'])->name('perwalian.export');
    Route::delete('/perwalian/{perwalian}', [AdminPerwalianController::class, 'destroy'])->name('perwalian.destroy');

    // Route Status Bimbingan (Admin Override)
    Route::get('/bimbingan', [AdminBimbinganController::class, 'index'])->name('bimbingan.index');
    Route::patch('/bimbingan/{bimbingan}/status', [AdminBimbinganController::class, 'updateStatus'])->name('bimbingan.update-status');
    Route::delete('/bimbingan/{bimbingan}', [AdminBimbinganController::class, 'destroy'])->name('bimbingan.destroy');
});

// ================= DOSEN =================
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    Route::get('/perwalian', [DosenPerwalianController::class, 'index'])->name('perwalian.index');
    
    // Update status perwalian oleh Dosen
    Route::put('/perwalian/{id}/status', [DosenPerwalianController::class, 'updateStatus'])->name('perwalian.updateStatus');
});

// ================= MAHASISWA =================
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/perwalian', [MahasiswaPerwalianController::class, 'index'])->name('perwalian.index');
    Route::get('/perwalian/create', [MahasiswaPerwalianController::class, 'create'])->name('perwalian.create');
    Route::post('/perwalian', [MahasiswaPerwalianController::class, 'store'])->name('perwalian.store');
});