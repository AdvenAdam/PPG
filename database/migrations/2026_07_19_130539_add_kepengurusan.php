<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // membuat table jabatan
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tingkat', ['desa', 'kelompok', 'daerah']);
            $table->timestamps();
        });

        // membuat tabel kepengurusan
        Schema::create('kepengurusan', function (Blueprint $table) {
            $table->id();
            // hanya bisa diisi salah satu dari 3 kolom ini, jika diisi lebih dari 1 maka akan error
            $table->foreignId('desa_id')->constrained('desa')->nullable()->onDelete('cascade');
            $table->foreignId('kelompok_id')->constrained('kelompok')->nullable()->onDelete('cascade');
            $table->foreignId('daerah_id')->constrained('daerah')->nullable()->onDelete('cascade');

            $table->string('nama');
            $table->foreignId('jabatan_id')->constrained('jabatan')->onDelete('cascade');
            $table->timestamps();
        });

        $this->seedJabatan();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepengurusan');
        Schema::dropIfExists('jabatan');
    }

    private function seedJabatan()
    {
        $jabatan = [
            // tingkat desa
            ['nama' => 'PPTD', 'tingkat' => 'desa'],
            ['nama' => 'Ketua', 'tingkat' => 'desa'],
            ['nama' => 'Wakil Ketua', 'tingkat' => 'desa'],
            ['nama' => 'Sekretaris', 'tingkat' => 'desa'],
            ['nama' => 'Bendahara', 'tingkat' => 'desa'],
            ['nama' => 'Penerobos', 'tingkat' => 'desa'],
            // tingkat kelompok
            ['nama' => 'PPTK', 'tingkat' => 'kelompok'],
            ['nama' => 'Ketua', 'tingkat' => 'kelompok'],
            ['nama' => 'Wakil Ketua', 'tingkat' => 'kelompok'],
            ['nama' => 'Sekretaris', 'tingkat' => 'kelompok'],
            ['nama' => 'Bendahara', 'tingkat' => 'kelompok'],
            ['nama' => 'Penerobos', 'tingkat' => 'kelompok'],

            ['nama' => 'Ketua', 'tingkat' => 'daerah'],
            ['nama' => 'Wakil Ketua', 'tingkat' => 'daerah'],
            ['nama' => 'Sekretaris', 'tingkat' => 'daerah'],
            ['nama' => 'Bendahara', 'tingkat' => 'daerah'],
            ['nama' => 'Tim Keilmuan', 'tingkat' => 'daerah'],
            ['nama' => 'Tim Mahasiswa dan Sarjana', 'tingkat' => 'daerah'],
            ['nama' => 'Tim Perlengkapan', 'tingkat' => 'daerah'],
        ];

        foreach ($jabatan as $j) {
            DB::table('jabatan')->insert($j);
        }
    }
};
