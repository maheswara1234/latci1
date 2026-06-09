<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpParser\Node\Stmt\Return_;

class TransaksiController extends BaseController
{
    public function index()
    {
        return view('v_keranjang');
    }
}
