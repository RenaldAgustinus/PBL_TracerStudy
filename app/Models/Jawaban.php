<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;

    protected $table = 'jawaban';
    protected $primaryKey = 'id_jawaban';
    public $timestamps = false;

    protected $fillable = [
        'id_survei',
        'id_pertanyaan',
        'nim_alumni',
        'id_pengguna_lulusan',
        'jawaban',
        'answered_at',
    ];
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'id_pertanyaan');
    }

    public function penggunalulusan()
    {
        return $this->belongsTo(PenggunaLulusan::class, 'id_pengguna_lulusan');
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'nim_alumni', 'nim');
    }
}
