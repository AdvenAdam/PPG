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
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_pengajian')->unsigned();
            $table->foreign('id_pengajian')->references('id')->on('pengajians');
            $table->bigInteger('id_kelas')->unsigned();
            $table->foreign('id_kelas')->references('id')->on('kelas');
            $table->longText('absen');
            $table->longText('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absens');
    }
};
