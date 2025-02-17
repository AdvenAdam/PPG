<?php

namespace App\Exports;

use App\Models\Desa;

class GenerusErrorImport
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $error;

    public function __construct($error)
    {
        $this->error = $error;
    }
}
