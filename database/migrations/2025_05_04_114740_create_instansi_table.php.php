<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instansi', function (Blueprint $table) {
            $table->id('id_instansi');
            $table->string('nama_instansi', 100)->nullable(); // Nama instansi
            $table->enum('jenis_instansi', ['Pendidikan Tinggi', 'Instansi Pemerintah', 'BUMN', 'Perusahaan Swasta'])->nullable(); // Jenis instansi
            $table->enum('skala_instansi', ['Wirausaha', 'Nasional', 'Multinasional'])->nullable(); // Skala instansi
            $table->string('lokasi_instansi', 100)->nullable(); // Lokasi instansi
            $table->string('no_hp_instansi', 20)->nullable(); // No HP instansi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
