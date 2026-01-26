<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('p_i_p_s', function (Blueprint $table) {
            $table->string('nomor_hp', 20)
                  ->nullable()
                  ->after('nama_ibu'); // bebas, sesuaikan posisi
        });
    }

    public function down(): void
    {
        Schema::table('p_i_p_s', function (Blueprint $table) {
            $table->dropColumn('nomor_hp');
        });
    }
};
