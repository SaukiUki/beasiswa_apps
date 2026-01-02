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
Schema::create('pip_usulans', function (Blueprint $table) {
    $table->id();

    $table->string('nisn')->index();

    // data yang boleh diusulkan
    $table->string('nama_siswa')->nullable();
    $table->string('nama_sekolah')->nullable();
    $table->decimal('nominal', 12, 2)->nullable();
    $table->string('status')->nullable();

    // workflow
    $table->enum('status_usulan', [
        'draft',
        'diajukan',
        'disetujui',
        'ditolak',
    ])->index();

    $table->text('catatan_admin')->nullable();

    // audit
    $table->timestamp('approved_at')->nullable();
    $table->foreignId('approved_by')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pip_usulans');
    }
};
