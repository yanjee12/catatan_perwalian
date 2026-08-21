<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class Bimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingans';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'tanggal_jadwal',
        'topik',
        'status',
        'catatan_dosen',
    ];

    protected $casts = [
        'tanggal_jadwal' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id')->withDefault([
            'nama' => '-',
            'nim'  => '-'
        ]);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id')->withDefault([
            'nama' => '-'
        ]);
    }
}