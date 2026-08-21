<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perwalians', function (Blueprint $table) {
            $table->string('semester', 10)->nullable()->after('tanggal_perwalian');
            $table->string('tahun_ajaran', 20)->nullable()->after('semester');
            $table->integer('sks_diambil')->nullable()->after('tahun_ajaran');
            $table->decimal('ipk', 3, 2)->nullable()->after('sks_diambil');
            $table->text('catatan')->nullable()->after('topik');
            $table->text('catatan_dosen')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('perwalians', function (Blueprint $table) {
            $table->dropColumn(['semester', 'tahun_ajaran', 'sks_diambil', 'ipk', 'catatan', 'catatan_dosen']);
        });
    }
};