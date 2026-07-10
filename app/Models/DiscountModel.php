<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table = 'discount';
    protected $primaryKey = 'id';
    // allowedFields menentukan kolom mana saja yang boleh diisi lewat form
    protected $allowedFields = ['tanggal', 'nominal', 'created_at', 'updated_at', 'deleted_at'];

    // Aktifkan auto timestamps karena kita punya kolom created_at dan updated_at
    protected $useTimestamps = true;
}