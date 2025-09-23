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
        if (!Schema::hasTable('sarpras')) {
            Schema::create('sarpras', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('id_kelompok')->unsigned();
                $table->foreign('id_kelompok')->references('id')->on('kelompok');
                $table->string('nama');
                $table->integer('jumlah');
                $table->json('kondisi')->nullable();
                $table->string('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_sarpras');
    }
};
