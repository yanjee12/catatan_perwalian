<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perwalians', function (Blueprint $table) {
            if (!Schema::hasColumn('perwalians', 'catatan')) {
                $table->text('catatan')->nullable();
            }
            if (!Schema::hasColumn('perwalians', 'catatan_dosen')) {
                $table->text('catatan_dosen')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('perwalians', function (Blueprint $table) {
            $table->dropColumn(['catatan', 'catatan_dosen']);
        });
    }
};