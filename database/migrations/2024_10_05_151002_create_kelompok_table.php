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

        Schema::create('kelompok', function (Blueprint $table) {
            $table->id(); // bigint unsigned
            $table->string('nama');
            $table->text('alamat');
            $table->bigInteger('id_desa')->unsigned(); // Pastikan ini juga unsigned
            $table->foreign('id_desa')->references('id')->on('desa')->onDelete('cascade');
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
        Schema::dropIfExists('kelompok');
        Schema::enableForeignKeyConstraints();
    }
};
