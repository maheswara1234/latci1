<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class DiscountController extends ResourceController
{
    use ResponseTrait;
    protected $modelName = 'App\Models\DiscountModel';
    protected $format = 'json';

    // (R) Read Semua Data
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // (R) Read Data Spesifik
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if ($data)
            return $this->respond($data);
        return $this->failNotFound('Data tidak ditemukan');
    }

    // (C) Create Data
    public function create()
    {
        // MENGGUNAKAN getJSON(true) UNTUK MENANGKAP RAW JSON
        $data = $this->request->getJSON(true);

        if ($this->model->insert($data)) {
            return $this->respondCreated($data, 'Data berhasil ditambahkan');
        }
        return $this->failValidationErrors($this->model->errors());
    }

    // (U) Update Data
    public function update($id = null)
    {
        // MENGGUNAKAN getJSON(true) UNTUK MENANGKAP RAW JSON
        $data = $this->request->getJSON(true);

        if ($this->model->update($id, $data)) {
            return $this->respondUpdated($data, 'Data berhasil diubah');
        }
        return $this->failValidationErrors($this->model->errors());
    }

    // (D) Delete Data
    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            $this->model->delete($id);
            return $this->respondDeleted($data, 'Data berhasil dihapus');
        }
        return $this->failNotFound('Data tidak ditemukan');
    }
}