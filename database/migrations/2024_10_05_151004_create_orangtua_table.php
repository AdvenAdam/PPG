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

        Schema::create('orangtua', function (Blueprint $table) {
            $table->id(); // bigint unsigned
            $table->string('nama_bapak');
            $table->tinyInteger('hum_bapak');
            $table->string('nama_ibu');
            $table->tinyInteger('hum_ibu');
            $table->string('alamat')->default('kelompok');
            $table->string('nohp');
            $table->bigInteger('id_kelompok')->unsigned(); // Pastikan ini juga unsigned
            $table->foreign('id_kelompok')->references('id')->on('kelompok')->onDelete('cascade');
            $table->timestamps();
        });


        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('orangtua');

        Schema::enableForeignKeyConstraints();
    }
};
