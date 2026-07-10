<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $waktuSekarang = date('Y-m-d H:i:s');

        // Looping 10 kali untuk hari ini dan 9 hari ke depan
        for ($i = 0; $i < 10; $i++) {

            // strtotime("+$i days") akan menambah hari secara berurutan tanpa duplikat
            $tanggal = date('Y-m-d', strtotime("+$i days"));

            // Membuat nominal acak antara 100rb, 150rb, 200rb, 250rb, 300rb
            $pilihanNominal = [100000, 150000, 200000, 250000, 300000];
            $nominal = $pilihanNominal[array_rand($pilihanNominal)];

            $data[] = [
                'tanggal' => $tanggal,
                'nominal' => $nominal,
                'created_at' => $waktuSekarang,
                'updated_at' => $waktuSekarang,
                'deleted_at' => null
            ];
        }

        // Memasukkan array $data ke dalam tabel discount sekaligus
        $this->db->table('discount')->insertBatch($data);
    }
}