<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    protected $productModel;

    function __construct()
    {
        helper(['number', 'form']);
        $this->productModel = new ProductModel();
    }

    public function index(): string
    {
        // 1. Menentukan tanggal hari ini dengan zona waktu lokal
        date_default_timezone_set('Asia/Jakarta');
        $hariIni = date('Y-m-d');

        // 2. Melakukan query ke tabel discount khusus untuk hari ini
        $db = \Config\Database::connect();
        $data['discount'] = $db->table('discount')->where('tanggal', $hariIni)->get()->getRowArray();

        // 3. Mengambil data produk (kode asli Anda tetap dipertahankan)
        $products = $this->productModel->findAll();
        $data['products'] = $products;

        // 4. Mengirimkan variabel judul halaman untuk Layout
        $data['hlm'] = 'Home';

        return view('v_home', $data);
    }

    public function contact(): string
    {
        return view('v_contact');
    }
}