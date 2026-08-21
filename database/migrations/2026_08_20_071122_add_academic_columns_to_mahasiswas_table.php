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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->decimal('ipk', 3, 2)->nullable()->after('program_studi');
            $table->integer('sks_diambil')->nullable()->after('ipk');
            $table->text('catatan')->nullable()->after('sks_diambil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['ipk', 'sks_diambil', 'catatan']);
        });
    }
};