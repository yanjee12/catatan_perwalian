<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perwalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->dateTime('tanggal_perwalian')->nullable();
            $table->string('topik')->nullable();
            $table->enum('status', ['diajukan', 'berlangsung', 'disetujui', 'ditolak'])->default('diajukan');
            $table->text('catatan_dosen')->nullable(); // Catatan balasan dari Dosen
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perwalians');
    }
};