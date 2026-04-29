<?php

namespace App\Http\Controllers\KepalaDesa;

use App\Http\Controllers\BaseAkunController;

class AkunController extends BaseAkunController
{
    public function __construct()
    {
        $this->akunRoute   = 'kepaladesa.akun.index';
        $this->viewPrefix  = 'kepala-desa';
    }
}