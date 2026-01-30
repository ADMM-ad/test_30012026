<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> Edit Produk
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Form Edit Produk -->
                        <?= form_open('produk/update/'.$produk->id_produk); ?>
                        
                            <!-- Nama Produk -->
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">
                                    Nama Produk <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control <?= form_error('nama_produk') ? 'is-invalid' : ''; ?>" 
                                       id="nama_produk" 
                                       name="nama_produk" 
                                       placeholder="Masukkan nama produk"
                                       value="<?= set_value('nama_produk', $produk->nama_produk); ?>">
                                <?php if(form_error('nama_produk')): ?>
                                    <div class="invalid-feedback">
                                        <?= form_error('nama_produk'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Harga -->
                            <div class="mb-3">
                                <label for="harga" class="form-label">
                                    Harga <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control <?= form_error('harga') ? 'is-invalid' : ''; ?>" 
                                       id="harga" 
                                       name="harga" 
                                       placeholder="Masukkan harga (angka saja)"
                                       value="<?= set_value('harga', $produk->harga); ?>">
                                <?php if(form_error('harga')): ?>
                                    <div class="invalid-feedback">
                                        <?= form_error('harga'); ?>
                                    </div>
                                <?php endif; ?>
                                <small class="text-muted">Contoh: 50000</small>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label for="kategori_id" class="form-label">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?= form_error('kategori_id') ? 'is-invalid' : ''; ?>" 
                                        id="kategori_id" 
                                        name="kategori_id">
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach($kategori as $k): ?>
                                        <option value="<?= $k->id_kategori; ?>" 
                                                <?= set_select('kategori_id', $k->id_kategori, ($k->id_kategori == $produk->kategori_id)); ?>>
                                            <?= $k->nama_kategori; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(form_error('kategori_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= form_error('kategori_id'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status_id" class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select <?= form_error('status_id') ? 'is-invalid' : ''; ?>" 
                                        id="status_id" 
                                        name="status_id">
                                    <option value="">-- Pilih Status --</option>
                                    <?php foreach($status as $s): ?>
                                        <option value="<?= $s->id_status; ?>" 
                                                <?= set_select('status_id', $s->id_status, ($s->id_status == $produk->status_id)); ?>>
                                            <?= $s->nama_status; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(form_error('status_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= form_error('status_id'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Button -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="<?= base_url('/index.php/produk'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>

                        <?= form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>