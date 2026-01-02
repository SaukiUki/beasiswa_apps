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
        Schema::table('pip_usulans', function (Blueprint $table) {
            Schema::table('pip_usulans', function (Blueprint $table) {
            $table->enum('origin', [
                'usulan_manual',
                'pip_utama',
            ])->default('usulan_manual')->index();
            $table->foreignId('pip_id')->nullable()->index();
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pip_usulans', function (Blueprint $table) {
            //
        });
    }
};
