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
        Schema::create('proker', function (Blueprint $table) {
            $table->id();
            $table->string('program');
            $table->bigInteger('id_tim')->unsigned();
            $table->foreign('id_tim')->references('id')->on('tim_proker');
            $table->longText('latar_belakang');
            $table->longText('tujuan');
            $table->longText('sasaran');
            $table->longText('target');
            $table->json('waktu_pelaksanaan');
            $table->string('penanggung_jawab');
            $table->longText('indikator_keberhasilan');
            $table->string('anggaran');
            $table->longText('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proker');
    }
};
