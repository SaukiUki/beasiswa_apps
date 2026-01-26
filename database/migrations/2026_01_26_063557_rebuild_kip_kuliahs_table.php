<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('kip_kuliahs');

        Schema::create('kip_kuliahs', function (Blueprint $table) {
            $table->id();

            // IDENTITAS UTAMA
            $table->string('pdid')->unique(); // 🔑 UNIQUE UTAMA
            $table->string('nama_mahasiswa'); // = nama_siswa
            $table->string('nama_perguruan_tinggi'); // = nama_sekolah

            // WILAYAH
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();

            // IDENTITAS
            $table->string('nik')->nullable();
            $table->string('nisn')->nullable();
            $table->string('npsn')->nullable(); // asal sekolah

            // AKADEMIK
            $table->string('kelas')->nullable(); // bisa diisi semester/angkatan
            $table->string('rombel')->nullable();
            $table->integer('semester')->nullable();
            $table->string('jenjang');
            $table->string('bentuk')->nullable(); // PTN / PTS

            // BIODATA
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();

            // ORANG TUA
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nomor_hp')->nullable();

            // BANTUAN
            $table->bigInteger('nominal')->nullable();

            // SK
            $table->string('tipe_sk')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->string('nomor_sk_nominasi')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->date('tanggal_sk_nominasi')->nullable();

            // TAHAP
            $table->string('tahap')->nullable();
            $table->string('tahap_nominasi')->nullable();

            // BANK
            $table->string('virtual_account')->nullable();
            $table->string('virtual_account_nominasi')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('bank')->nullable();

            // WAKTU PENCAIRAN
            $table->date('tanggal_aktifasi')->nullable();
            $table->date('tanggal_mulai_pencairan')->nullable();
            $table->date('tanggal_cair')->nullable();

            // NOMOR BANTUAN SOSIAL
            $table->string('no_kip')->nullable();
            $table->string('no_kks')->nullable();
            $table->string('no_kps')->nullable();
            $table->string('no_pkh')->nullable();

            // KELAYAKAN & PENGUSUL
            $table->boolean('layak_pip')->nullable();
            $table->string('nama_pengusul')->nullable();
            $table->string('nama_pengusul_utama')->nullable();

            // FASE & KETERANGAN
            $table->string('fase')->nullable();
            $table->text('keterangan_tahap')->nullable();
            $table->text('keterangan_pencairan')->nullable();
            $table->text('keterangan_tambahan')->nullable();

            // STATUS
            $table->string('status')->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kip_kuliahs');
    }
};
