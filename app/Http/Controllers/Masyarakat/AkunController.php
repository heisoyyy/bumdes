<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\BaseAkunController;

class AkunController extends BaseAkunController
{
    public function __construct()
    {
        $this->akunRoute   = 'masyarakat.akun.index';
        $this->viewPrefix  = 'masyarakat';
    }
}