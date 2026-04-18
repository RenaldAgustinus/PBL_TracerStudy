<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
       Schema::create('pengguna_lulusan', function (Blueprint $table) {
    $table->id('id_pengguna_lulusan');
    $table->string('nama_pl', 100)->nullable();
    $table->string('instansi', 100)->nullable();
    $table->string('jabatan', 100)->nullable();
    $table->string('email', 100)->nullable();
    $table->string('nama_alumni', 100)->nullable();
    $table->string('nim', 20)->nullable(); // <--- CUKUP SATU SAJA YANG INI
    $table->string('nama_atasan', 100)->nullable();
    $table->string('jabatan_atasan', 100)->nullable();
    $table->string('email_atasan', 100)->nullable();
    $table->string('otp', 6)->nullable();
    $table->timestamps();
});
    }

    public function down(): void {
        Schema::dropIfExists('pengguna_lulusan');
    }
};