<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashData('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="row">
  <?php foreach ($products as $p): ?>
    <?php
    $hargaAsli = $p['harga'];
    $hargaTampil = $hargaAsli;
    $adaDiskon = false;

    if (!empty($discount)) {
      $adaDiskon = true;
      $hargaTampil = $hargaAsli - $discount['nominal'];
    }
    ?>

    <div class="col-md-4 mb-4">
      <div class="card h-100 shadow-sm border-0">
        <img src="<?= base_url('img/' . $p['foto']) ?>" class="card-img-top p-3" alt="<?= $p['nama'] ?>"
          style="object-fit: contain; max-height: 200px;">

        <div class="card-body">
          <h5 class="card-title p-0 mb-3" style="font-size: 16px; font-weight: 600;"><?= $p['nama'] ?></h5>
          <p class="card-text mb-4" style="font-size: 14px;">
            <?php if ($adaDiskon): ?>
              <span class="text-danger text-decoration-line-through me-1">
                IDR <?= number_format($hargaAsli, 0, ',', '.') ?>
              </span>
              <span class="fw-bold" style="color: #4154f1;">
                IDR <?= number_format($hargaTampil, 0, ',', '.') ?>
              </span>
            <?php else: ?>
              <span class="fw-bold" style="color: #4154f1;">
                IDR <?= number_format($hargaAsli, 0, ',', '.') ?>
              </span>
            <?php endif; ?>
          </p>

          <?= form_open('keranjang') ?>
          <?= form_hidden('id', (string) $p['id']); ?>
          <?= form_hidden('nama', $p['nama']); ?>
          <?= form_hidden('harga', (string) $hargaTampil); ?>
          <?= form_hidden('foto', $p['foto']); ?>

          <button type="submit" class="btn btn-info text-white rounded-pill px-4 btn-sm">
            Beli
          </button>
          <?= form_close() ?>
        </div>
      </div>
    </div>

  <?php endforeach; ?>
</div>

<?= $this->endSection() ?>