<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jawaban', function (Blueprint $table) {
            $table->id('id_jawaban');
            $table->unsignedBigInteger('id_pertanyaan');
            $table->string('nim_alumni', 20)->nullable(); // jika diisi alumni
            $table->unsignedBigInteger('id_pengguna_lulusan')->nullable(); // jika diisi pengguna lulusan
            $table->text('jawaban');
            $table->timestamp('answered_at')->useCurrent();

            // Foreign Key Constraints
            $table->foreign('id_pertanyaan')->references('id_pertanyaan')->on('pertanyaan');
            $table->foreign('nim_alumni')->references('nim')->on('alumni')->nullOnDelete();
            $table->foreign('id_pengguna_lulusan')->references('id_pengguna_lulusan')->on('pengguna_lulusan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban');
    }
};