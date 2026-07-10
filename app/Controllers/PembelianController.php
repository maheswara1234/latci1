<?php
namespace App\Controllers;

class PembelianController extends BaseController
{
    public function index()
    {
        // Proteksi: Hanya admin yang boleh masuk
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        // Mengambil semua riwayat transaksi
        $transaksi = $db->table('transaction')->orderBy('created_at', 'DESC')->get()->getResultArray();

        // Mengambil detail setiap transaksi beserta nama dan foto produk
        $detail_transaksi = [];
        foreach ($transaksi as $t) {
            $details = $db->table('transaction_detail')
                ->select('transaction_detail.*, product.nama, product.foto')
                ->join('product', 'product.id = transaction_detail.product_id')
                ->where('transaction_id', $t['id'])
                ->get()->getResultArray();
            $detail_transaksi[$t['id']] = $details;
        }

        $data = [
            'hlm' => 'Pembelian',
            'transaksi' => $transaksi,
            'detail_transaksi' => $detail_transaksi
        ];

        return view('v_pembelian', $data);
    }

    public function ubah_status($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();
        $transaksi = $db->table('transaction')->where('id', $id)->get()->getRowArray();

        if ($transaksi) {
            // Logika Toggle: Jika 0 ubah ke 1, jika 1 ubah ke 0
            $newStatus = ($transaksi['status'] == 0) ? 1 : 0;
            $db->table('transaction')->where('id', $id)->update(['status' => $newStatus]);
            session()->setFlashdata('success', 'Status pesanan berhasil diubah.');
        }

        return redirect()->to('/pembelian');
    }
}