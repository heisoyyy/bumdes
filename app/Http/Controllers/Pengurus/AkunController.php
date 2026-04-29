<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\BaseAkunController;

class AkunController extends BaseAkunController
{
    public function __construct()
    {
        $this->akunRoute   = 'pengurus.akun.index';
        $this->viewPrefix  = 'pengurus';
    }
}