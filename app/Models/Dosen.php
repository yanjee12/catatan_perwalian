<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nip', 'nama', 'email', 'no_hp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function perwalians()
    {
        return $this->hasMany(Perwalian::class);
    }
}
