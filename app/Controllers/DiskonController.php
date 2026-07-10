<?php

namespace App\Controllers;

use App\Models\DiscountModel;

class DiskonController extends BaseController
{
    protected $discountModel;

    public function __construct()
    {
        $this->discountModel = new DiscountModel();
    }

    public function index()
    {
        // Proteksi: Jika bukan admin, tendang kembali ke halaman Home
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $data = [
            'hlm' => 'Diskon',
            'diskon' => $this->discountModel->findAll()
        ];

        return view('v_diskon', $data);
    }

    public function store()
    {
        // Validasi: Tanggal harus diisi dan tidak boleh sama dengan yang sudah ada di database
        if (
            !$this->validate([
                'tanggal' => [
                    'rules' => 'required|is_unique[discount.tanggal]',
                    'errors' => [
                        'is_unique' => 'The tanggal field must contain a unique value.'
                    ]
                ],
                'nominal' => 'required|numeric'
            ])
        ) {
            // Jika validasi gagal, kembalikan ke halaman diskon beserta pesan errornya
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Jika validasi berhasil, simpan ke database
        $this->discountModel->save([
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal')
        ]);

        return redirect()->to('/diskon');
    }

    public function update($id)
    {
        // Untuk update, kita hanya mengubah nominalnya karena tanggal bersifat readonly
        $this->discountModel->save([
            'id' => $id,
            'nominal' => $this->request->getPost('nominal')
        ]);

        return redirect()->to('/diskon');
    }

    public function delete($id)
    {
        $this->discountModel->delete($id);
        return redirect()->to('/diskon');
    }
}