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
        Schema::table('perwalians', function (Blueprint $table) {
            // Menambahkan kolom status jika belum ada
            if (!Schema::hasColumn('perwalians', 'status')) {
                $table->enum('status', ['diajukan', 'berlangsung', 'disetujui', 'ditolak'])
                      ->default('diajukan')
                      ->after('topik');
            }

            // Menambahkan kolom catatan_dosen jika belum ada
            if (!Schema::hasColumn('perwalians', 'catatan_dosen')) {
                $table->text('catatan_dosen')
                      ->nullable()
                      ->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perwalians', function (Blueprint $table) {
            if (Schema::hasColumn('perwalians', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('perwalians', 'catatan_dosen')) {
                $table->dropColumn('catatan_dosen');
            }
        });
    }
};