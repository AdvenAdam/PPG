<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajians', function (Blueprint $table) {
            $table->enum('tingkat', ['kelompok', 'desa', 'daerah'])->default('kelompok');            
        });
        DB::table('pengajians')->update(['tingkat' => 'kelompok']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
