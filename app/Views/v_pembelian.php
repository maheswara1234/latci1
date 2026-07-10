<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<h5 class="mb-4">History Transaksi Pembelian</h5>

<table class="table datatable">
    <thead>
        <tr>
            <th>#</th>
            <th>ID Pembelian</th>
            <th>Pembeli</th>
            <th>Waktu Pembelian</th>
            <th>Total Bayar</th>
            <th>Alamat</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;
        foreach ($transaksi as $t): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $t['id'] ?></td>
                <td><?= $t['username'] ?></td>
                <td><?= $t['created_at'] ?></td>
                <td>IDR <?= number_format($t['total_harga'], 0, ',', '.') ?></td>
                <td><?= $t['alamat'] ?></td>
                <td>
                    <?php if ($t['status'] == 0): ?>
                        <span class="badge bg-warning text-dark">Belum Selesai</span>
                    <?php else: ?>
                        <span class="badge bg-primary">Sudah Selesai</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#detailModal<?= $t['id'] ?>">Detail</button>
                    <a href="<?= base_url('pembelian/status/' . $t['id']) ?>" class="btn btn-info btn-sm text-white">Ubah
                        Status</a>
                </td>
            </tr>

            <div class="modal fade" id="detailModal<?= $t['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Transaksi #<?= $t['id'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php $no = 1;
                            foreach ($detail_transaksi[$t['id']] as $d): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3"><?= $no++ ?>)</div>
                                    <img src="<?= base_url('img/' . $d['foto']) ?>" width="60" class="me-3 rounded border">
                                    <div>
                                        <strong style="font-size: 14px;"><?= $d['nama'] ?></strong><br>
                                        <small class="text-muted">(<?= $d['jumlah'] ?> pcs)</small><br>
                                        <small>IDR <?= number_format($d['subtotal_harga'], 0, ',', '.') ?></small>
                                    </div>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                            <div class="text-end mt-3">
                                <span>Ongkir: IDR <?= number_format($t['ongkir'], 0, ',', '.') ?></span><br>
                                <strong class="fs-5" style="color: #4154f1;">Total: IDR
                                    <?= number_format($t['total_harga'], 0, ',', '.') ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>