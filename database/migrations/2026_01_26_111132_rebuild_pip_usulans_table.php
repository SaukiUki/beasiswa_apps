<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('pip_usulans');

        Schema::create('pip_usulans', function (Blueprint $table) {
            $table->id();

            // ===== DATA SAMA DENGAN PIP =====
            $table->string('pdid')->unique();
            $table->string('nama_siswa')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('nik')->nullable();
            $table->string('nisn')->index();
            $table->string('npsn')->nullable();
            $table->string('kelas')->nullable();
            $table->string('rombel')->nullable();
            $table->string('semester')->nullable();
            $table->string('jenjang')->nullable();
            $table->string('bentuk')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->decimal('nominal', 12, 2)->nullable();

            $table->string('tipe_sk')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->string('nomor_sk_nominasi')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->date('tanggal_sk_nominasi')->nullable();

            $table->string('tahap')->nullable();
            $table->string('tahap_nominasi')->nullable();

            $table->string('virtual_account')->nullable();
            $table->string('virtual_account_nominasi')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('bank')->nullable();

            $table->date('tanggal_aktifasi')->nullable();
            $table->date('tanggal_mulai_pencairan')->nullable();
            $table->date('tanggal_cair')->nullable();

            $table->string('no_kip')->nullable();
            $table->string('no_kks')->nullable();
            $table->string('no_kps')->nullable();
            $table->string('no_pkh')->nullable();

            $table->boolean('layak_pip')->nullable();

            $table->string('nama_pengusul')->nullable();
            $table->string('nama_pengusul_utama')->nullable();

            $table->string('fase')->nullable();

            $table->text('keterangan_tahap')->nullable();
            $table->text('keterangan_pencairan')->nullable();
            $table->text('keterangan_tambahan')->nullable();

            $table->string('status')->nullable();

            // ===== WORKFLOW =====
            $table->enum('status_usulan', [
                'draft',
                'diajukan',
                'disetujui',
                'ditolak',
            ])->default('draft')->index();

            $table->text('catatan_admin')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pip_usulans');
    }
};

