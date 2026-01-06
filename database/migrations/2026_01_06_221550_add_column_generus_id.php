<?php

use App\Models\Desa;
use App\Models\Generus;
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
        Schema::table('generus', function (Blueprint $table) {
            $table->string('generus_id')->nullable();
        });

        $this->seeder();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }

    private function seeder()
    {
        $generus = Generus::orderBy('id_desa')->orderBy('id_kelompok')->orderBy('id_kelas')->get();

        $grouped = $generus->groupBy('id_kelompok');

        foreach ($grouped as $kelompokId => $items) {
            $counter = 1;
            foreach ($items as $item) {
                $item->generus_id = $item->id_desa
                    . sprintf("%02d", $item->id_kelompok)
                    . sprintf("%02d", $item->id_kelas)
                    . sprintf("%03d", $counter);
                $item->save();
                $counter++;
            }
        }
    }
};
