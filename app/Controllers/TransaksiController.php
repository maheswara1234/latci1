<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\RajaOngkirService;
use CodeIgniter\HTTP\ResponseInterface;
use PhpParser\Node\Stmt\Return_;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        // Set timezone agar konsisten
        date_default_timezone_set('Asia/Jakarta');
    }

    // Fungsi bantuan untuk mengambil nominal diskon hari ini
    private function getDiskonHariIni()
    {
        $db = \Config\Database::connect();
        $diskon = $db->table('discount')->where('tanggal', date('Y-m-d'))->get()->getRowArray();
        return $diskon ? $diskon['nominal'] : 0;
    }

    public function index()
    {
        $diskonNominal = $this->getDiskonHariIni();
        $items = $this->cart->contents();
        $totalBelanja = 0;

        // Kalkulasi ulang total berdasarkan diskon
        foreach ($items as $item) {
            $hargaFix = $item['price'] - $diskonNominal;
            $totalBelanja += ($hargaFix * $item['qty']);
        }

        $data = [
            'hlm' => 'Keranjang',
            'items' => $items,
            'total' => $totalBelanja,
            'diskonNominal' => $diskonNominal
        ];

        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $this->cart->insert([
            'id' => $this->request->getPost('id'),
            'qty' => 1,
            'price' => $this->request->getPost('harga'),
            'name' => $this->request->getPost('nama'),
            'options' => [
                'foto' => $this->request->getPost('foto')
            ]
        ]);

        session()->setFlashdata(
            'success',
            'Produk berhasil ditambahkan ke keranjang. 
        <a href="' . base_url('keranjang') . '">Lihat</a>'
        );

        return redirect()->to(base_url('/'));
    }

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $item) {
            $qty = $this->request->getPost('qty' . $i++);

            $this->cart->update([
                'rowid' => $item['rowid'],
                'qty' => $qty
            ]);
        }

        session()->setFlashdata('success', 'Keranjang berhasil diperbarui');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setFlashdata('success', 'Produk berhasil dihapus dari keranjang');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setFlashdata('success', 'Keranjang berhasil dikosongkan');
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $diskonNominal = $this->getDiskonHariIni();
        $items = $this->cart->contents();
        $totalBelanja = 0;

        foreach ($items as $item) {
            $hargaFix = $item['price'] - $diskonNominal;
            $totalBelanja += ($hargaFix * $item['qty']);
        }

        $data = [
            'hlm' => 'Checkout',
            'items' => $items,
            'total' => $totalBelanja,
            'diskonNominal' => $diskonNominal
        ];

        return view('v_checkout', $data);
    }

    public function destinations()
    {
        $search = $this->request->getGet('q');
        $service = new RajaOngkirService();
        $response = $service->getDestination($search);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'id' => $item['id'],
                'text' => $item['label']
            ];
        }

        return $this->response->setJSON(['results' => $results]);
    }

    public function costs()
    {
        $origin = '64999';
        $destination = $this->request->getGet('destination');
        $weight = '1000';
        $courier = 'jne';

        $service = new RajaOngkirService();
        $response = $service->getCost($origin, $destination, $weight, $courier);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'service' => $item['service'],
                'description' => $item['description'],
                'cost' => $item['cost'],
                'etd' => $item['etd']
            ];
        }

        return $this->response->setJSON($results);
    }

    public function buy()
    {
        $cartItems = $this->cart->contents();
        if (empty($cartItems)) {
            return redirect()->back();
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $diskonNominal = $this->getDiskonHariIni();
        $subtotal = 0;

        // Kalkulasi subtotal dengan harga yang sudah didiskon
        foreach ($cartItems as $item) {
            $hargaFix = $item['price'] - $diskonNominal;
            $subtotal += ($item['qty'] * $hargaFix);
        }

        $ongkir = (int) $this->request->getPost('ongkir');

        // Insert Transaction (Total Harga mencakup diskon)
        $transaction = [
            'username' => $this->request->getPost('username'),
            'alamat' => $this->request->getPost('alamat'),
            'ongkir' => $ongkir,
            'total_harga' => $subtotal + $ongkir,
            'status' => 0,
        ];

        if (!$this->transactionModel->insert($transaction)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal membuat transaksi');
        }

        $transactionId = $this->transactionModel->getInsertID();

        // Insert Transaction Detail (Menyimpan nilai diskon & subtotal baru)
        foreach ($cartItems as $item) {
            $hargaFix = $item['price'] - $diskonNominal;

            $this->transactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'product_id' => $item['id'],
                'jumlah' => $item['qty'],
                'diskon' => $diskonNominal,
                'subtotal_harga' => $item['qty'] * $hargaFix
            ]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->with('error', 'Gagal membuat transaksi');
        }

        $this->cart->destroy();
        return redirect()->to(base_url());
    }
}