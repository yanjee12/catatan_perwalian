<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dosen_id',
        'nim',
        'nama',
        'email',
        'no_hp',
        'angkatan',
        'program_studi',
        'ipk',
        'sks_diambil',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function perwalians()
    {
        return $this->hasMany(Perwalian::class, 'mahasiswa_id');
    }

    // Helper untuk mengambil 1 data perwalian terbaru
    public function perwalianTerbaru()
    {
        return $this->hasOne(Perwalian::class, 'mahasiswa_id')->latestOfMany();
    }
}