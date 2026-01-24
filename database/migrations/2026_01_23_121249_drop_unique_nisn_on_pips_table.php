<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('p_i_p_s', function (Blueprint $table) {
            // DROP unique index NISN
            $table->dropUnique(['nisn']);
        });
    }

    public function down(): void
    {
        Schema::table('p_i_p_s', function (Blueprint $table) {
            // rollback: jadikan unik lagi kalau perlu
            $table->unique('nisn');
        });
    }
};
