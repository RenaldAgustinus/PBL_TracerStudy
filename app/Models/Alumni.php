<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumni extends Model
{
    use HasFactory;
    protected $table = 'alumni'; // Nama tabel
    protected $primaryKey = 'nim'; // Primary key
    public $incrementing = false; // Karena primary key bukan auto-increment
    public $timestamps = false; // Nonaktifkan timestamps

    protected $fillable = [
        'nim',
        'nama_alumni',
        'id_prodi',  // Ganti dari 'prodi'
        'no_hp',
        'email',
        'tahun_masuk',
        'tgl_lulus',
        'tanggal_kerja_pertama',
        'tanggal_mulai_instansi',
        'masa_tunggu',
        'id_profesi', // Ganti dari 'profesi' dan 'kategori_profesi'
        'id_pengguna_lulusan',
        'id_instansi',
    ];

    /**
     * Relasi ke tabel pengguna_lulusan
     * Satu alumni memiliki satu pengguna lulusan
     */
    public function penggunaLulusan()
    {
        return $this->belongsTo(PenggunaLulusan::class, 'id_pengguna_lulusan', 'id_pengguna_lulusan');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi', 'id_instansi');
    }

    public function jawaban()
    {
        return $this->hasOne(Jawaban::class, 'nim_alumni', 'nim');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function profesi()
    {
        return $this->belongsTo(Profesi::class, 'id_profesi');
    }
}
