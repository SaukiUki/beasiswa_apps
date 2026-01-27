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

            // Identitas
            $table->string('no_pendaftaran')->nullable();
            $table->string('nama_siswa');
            $table->string('nik', 16)->nullable();
            $table->string('no_kartu_keluarga', 16)->nullable();
            $table->string('nik_kepala_keluarga', 16)->nullable();
            $table->string('nisn', 10)->nullable();

            // Status bantuan
            $table->boolean('status_dtks')->default(false);
            $table->boolean('status_p3ke')->default(false);

            // Bantuan
            $table->string('no_kip')->nullable();
            $table->string('no_kks')->nullable();

            // Sekolah
            $table->string('asal_sekolah')->nullable();
            $table->string('kab_kota_sekolah')->nullable();
            $table->string('provinsi_sekolah')->nullable();

            // Pribadi
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();

            // Kontak
            $table->text('alamat_tinggal')->nullable();
            $table->string('no_handphone')->nullable();
            $table->string('email')->nullable();

            // Ayah
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->integer('penghasilan_ayah')->nullable();
            $table->string('status_ayah')->nullable();

            // Ibu
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->integer('penghasilan_ibu')->nullable();
            $table->string('status_ibu')->nullable();

            // Ekonomi
            $table->integer('jumlah_tanggungan')->nullable();
            $table->string('kepemilikan_rumah')->nullable();
            $table->year('tahun_perolehan')->nullable();
            $table->string('sumber_listrik')->nullable();
            $table->integer('luas_tanah')->nullable();
            $table->integer('luas_bangunan')->nullable();
            $table->string('sumber_air')->nullable();
            $table->string('mck')->nullable();
            $table->decimal('jarak_pusat_kota_km', 8, 2)->nullable();

            // Pengajuan
            $table->string('diusulkan_oleh')->nullable();
            $table->string('pt_tujuan')->nullable();
            $table->string('prodi_rekomendasi')->nullable();
            $table->string('status_pengajuan')->nullable();
            $table->year('tahun')->nullable();
            $table->text('rekomendasi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kip_kuliahs');
    }
};
