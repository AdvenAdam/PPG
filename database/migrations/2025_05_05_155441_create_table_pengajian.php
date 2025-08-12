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
        Schema::create('pengajians', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->datetime('waktu_tanggal_mulai');
            $table->bigInteger('id_kelompok')->unsigned();
            $table->foreign('id_kelompok')->references('id')->on('kelompok');
            $table->text('materi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajians');
    }
};
