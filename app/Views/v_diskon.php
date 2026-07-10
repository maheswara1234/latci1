<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif ?>

<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
    Tambah Data
</button>

<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Nominal</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;
        foreach ($diskon as $d): ?>
            <tr>
                <th scope="row"><?= $i++ ?></th>
                <td><?= $d['tanggal'] ?></td>
                <td><?= $d['nominal'] ?></td>
                <td>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editModal<?= $d['id'] ?>">Ubah</button>
                    <a href="<?= base_url('/diskon/delete/' . $d['id']) ?>" class="btn btn-danger btn-sm"
                        onclick="return confirm('Yakin ingin menghapus diskon ini?')">Hapus</a>
                </td>
            </tr>

            <div class="modal fade" id="editModal<?= $d['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('/diskon/update/' . $d['id']) ?>" method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="text" class="form-control" name="tanggal" value="<?= $d['tanggal'] ?>"
                                        readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nominal</label>
                                    <input type="number" class="form-control" name="nominal" value="<?= $d['nominal'] ?>"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('/diskon/store') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <input type="number" class="form-control" name="nominal" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>