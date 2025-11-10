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
        Schema::create('surat_aktifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswas_id')->constrained()->cascadeOnDelete();
            $table->foreignId('approvals_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tahun_akademiks_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_aktifs');
    }
};
