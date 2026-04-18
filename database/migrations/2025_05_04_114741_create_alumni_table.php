<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumni', function (Blueprint $table) {
            $table->string('nim', 20)->primary(); // Primary key
            $table->string('nama_alumni', 100); // Nama alumni
            $table->unsignedBigInteger('id_prodi'); // Foreign key ke prodi
            $table->string('no_hp', 20)->nullable(); // Nomor HP alumni
            $table->string('email', 100)->nullable(); // Email alumni
            $table->year('tahun_masuk')->nullable(); // Tahun masuk
            $table->date('tgl_lulus'); // Tanggal lulus
            $table->date('tanggal_kerja_pertama')->nullable(); // Tanggal kerja pertama
            $table->date('tanggal_mulai_instansi')->nullable(); // Tanggal mulai instansi
            $table->integer('masa_tunggu')->nullable(); // Masa tunggu kerja
            $table->unsignedBigInteger('id_profesi')->nullable(); // Foreign key ke profesi
            $table->unsignedBigInteger('id_pengguna_lulusan')->nullable(); // Foreign key ke pengguna_lulusan
            $table->unsignedBigInteger('id_instansi')->nullable(); // Foreign key ke instansi

            // Foreign Key Constraints
            $table->foreign('id_prodi')
                ->references('id_prodi')
                ->on('prodi')
                ->onDelete('restrict'); // Tidak boleh hapus prodi yang masih digunakan

            $table->foreign('id_profesi')
                ->references('id_profesi')
                ->on('profesi')
                ->onDelete('set null'); // Jika profesi dihapus, set null

            $table->foreign('id_pengguna_lulusan')
                ->references('id_pengguna_lulusan')
                ->on('pengguna_lulusan')
                ->onDelete('set null'); // Jika pengguna_lulusan dihapus, set null

            $table->foreign('id_instansi')
                ->references('id_instansi')
                ->on('instansi')
                ->onDelete('set null'); // Jika instansi dihapus, set null
        });
    }

    public function down(): void {
        Schema::dropIfExists('alumni');
    }
};