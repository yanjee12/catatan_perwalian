<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perwalian extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (opsional jika mengikuti konvensi penamaan standar Laravel).
     */
    protected $table = 'perwalians';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'tanggal_perwalian',
        'semester',
        'tahun_ajaran',
        'sks_diambil',
        'ipk',
        'topik',
        'catatan',
        'status',
    ];

    /**
     * Casting tipe data atribut.
     */
    protected $casts = [
        'tanggal_perwalian' => 'datetime',
    ];

    /**
     * Relasi ke model Mahasiswa (Setiap data perwalian dimiliki oleh satu mahasiswa).
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke model Dosen (Setiap data perwalian ditujukan kepada satu dosen wali).
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}