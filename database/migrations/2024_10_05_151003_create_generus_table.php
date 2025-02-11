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
        Schema::disableForeignKeyConstraints();

        Schema::create('jamaah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tgllahir');
            $table->enum('gender', ['L', 'P']);
            // NOTE : kelompok and desa have 1 to 1 relationship
            $table->bigInteger('id_desa')->unsigned();
            $table->foreign('id_desa')->references('id')->on('desa');
            $table->bigInteger('id_kelompok')->unsigned();
            $table->foreign('id_kelompok')->references('id')->on('kelompok');
            $table->bigInteger('id_kelas')->unsigned();
            $table->foreign('id_kelas')->references('id')->on('kelas');
            $table->enum('pendidikan_terakhir', ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']);
            $table->enum('status_pekerjaan', ['PELAJAR/MAHASISWA', 'BEKERJA', 'BELUM BEKERJA']);
            $table->string('detail_pekerjaan')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_bapak')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->text('keterangan')->nullable();
            $table->string('foto_url')->default('user.png');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaah');
    }
};
