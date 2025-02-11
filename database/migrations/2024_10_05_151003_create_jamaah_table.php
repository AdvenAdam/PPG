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
            $table->string('username');
            $table->date('tgllahir');
            $table->integer('gender');
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('id_pekerjaan')->unsigned();
            $table->foreign('id_pekerjaan')->references('id')->on('pekerjaan');
            $table->bigInteger('id_kelompok')->unsigned();
            $table->foreign('id_kelompok')->references('id')->on('kelompok');
            $table->tinyInteger('mubalight')->default(0);
            $table->tinyInteger('haji')->default(0);
            $table->tinyInteger('agniya')->default(0);
            $table->tinyInteger('duafa')->default(0);
            $table->tinyInteger('yatim')->default(0);
            $table->tinyInteger('mahasiswa')->default(0);
            $table->tinyInteger('sarjana')->default(0);
            $table->bigInteger('id_kelas')->unsigned();
            $table->foreign('id_kelas')->references('id')->on('kelas');
            $table->bigInteger('id_orangtua')->unsigned();
            $table->foreign('id_orangtua')->references('id')->on('orangtua');
            $table->string('foto')->default('user.png');
            $table->tinyInteger('meninggal')->default(0);
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
