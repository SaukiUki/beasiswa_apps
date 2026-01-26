<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key constraints for SQLite
        DB::statement('PRAGMA foreign_keys = OFF');

        // Drop the table and recreate with new structure
        Schema::dropIfExists('pendidikans');

        Schema::create('pendidikans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah'); // nama_instansi -> nama_sekolah
            $table->string('jenjang_instansi')->nullable(); // Jenjang (SD, SMP, SMA, etc)
            $table->foreignId('kota_id')->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->string('alamat');
            $table->string('nama_kepsek')->nullable(); // nama_pimpinan -> nama_kepsek
            $table->string('nama_operator')->nullable();
            $table->string('no_hp_kepsek')->nullable(); // no_hp_pimpinan -> no_hp_kepsek
            $table->string('no_hp_operator')->nullable();
            $table->integer('jumlah_siswa')->nullable()->default(0);
            $table->integer('jumlah_pip_aspirasi')->nullable()->default(0);
            $table->timestamps();
        });

        // Re-enable foreign key constraints
        DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::dropIfExists('pendidikans');

        Schema::create('pendidikans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi');
            $table->string('jenjang_pendidikan');
            $table->foreignId('kota_id')->constrained()->onDelete('cascade');
            $table->foreignId('kecamatan_id')->constrained()->onDelete('cascade');
            $table->foreignId('kelurahan_id')->constrained()->onDelete('cascade');
            $table->string('alamat');
            $table->string('nama_pimpinan')->nullable();
            $table->string('no_hp_pimpinan')->nullable();
            $table->string('email_instansi')->nullable();
            $table->timestamps();
        });

        DB::statement('PRAGMA foreign_keys = ON');
    }
};

