<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesi extends Model
{
    use HasFactory;

    protected $table = 'profesi';
    protected $primaryKey = 'id_profesi';
    
    protected $fillable = [
        'nama_profesi',
        'kategori_profesi'
    ];

    public function alumni()
    {
        return $this->hasMany(Alumni::class, 'id_profesi');
    }
}