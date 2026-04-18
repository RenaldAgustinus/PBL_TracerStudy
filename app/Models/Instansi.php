<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansi'; // Nama tabel
    protected $primaryKey = 'id_instansi'; // Primary key
    public $timestamps = false; // Nonaktifkan timestamps

    protected $fillable = [
        'nama_instansi',
        'jenis_instansi',
        'skala_instansi',
        'lokasi_instansi',
        'no_hp_instansi',
    ];

    public function alumni()
    {
        return $this->hasMany(Alumni::class, 'id_instansi', 'id_instansi');
    }
}
