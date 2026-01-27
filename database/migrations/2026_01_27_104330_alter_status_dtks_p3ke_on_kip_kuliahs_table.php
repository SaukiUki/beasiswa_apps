<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kip_kuliahs', function (Blueprint $table) {
            // HAPUS kolom boolean lama
            if (Schema::hasColumn('kip_kuliahs', 'status_dtks')) {
                $table->dropColumn('status_dtks');
            }

            if (Schema::hasColumn('kip_kuliahs', 'status_p3ke')) {
                $table->dropColumn('status_p3ke');
            }
        });

        Schema::table('kip_kuliahs', function (Blueprint $table) {
            // TAMBAH ulang sebagai string
            $table->string('status_dtks')->nullable();
            $table->string('status_p3ke')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kip_kuliahs', function (Blueprint $table) {
            $table->dropColumn(['status_dtks', 'status_p3ke']);
        });

        Schema::table('kip_kuliahs', function (Blueprint $table) {
            $table->boolean('status_dtks')->default(false);
            $table->boolean('status_p3ke')->default(false);
        });
    }
};
