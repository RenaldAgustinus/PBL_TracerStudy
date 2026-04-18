<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pertanyaan', function (Blueprint $table) {
            $table->id('id_pertanyaan');
            $table->text('isi_pertanyaan');
            $table->enum('kategori', ['tracer', 'pengguna_lulusan', 'umum']);
            $table->enum('metodejawaban', ['1', '2',]);
            $table->unsignedBigInteger('created_by'); // FK ke admin
            $table->foreign('created_by')->references('id_admin')->on('admin');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pertanyaan');
    }
};