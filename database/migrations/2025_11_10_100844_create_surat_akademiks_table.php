
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
        Schema::create('surat_akademiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_studi_id')->constrained()->cascadeOnDelete();
            $table->string('fakultas')->nullable();
            $table->string('npm')->nullable();
            $table->string('angkatan_tahun')->nullable();
            $table->string('semester')->nullable();
            $table->string('belum_sudah_cuti')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('permohonan')->nullable();
            $table->text('alasan_cuti')->nullable();
            $table->string('tahun_akademik')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_akademiks');
    }
};
