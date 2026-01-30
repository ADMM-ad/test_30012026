<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Bisa Dijual</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-box"></i> Data Produk - Bisa Dijual
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Flash Message Success -->
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Flash Message Error -->
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Button Tambah -->
                        <div class="mb-3">
                            <a href="<?= base_url('index.php/produk/tambah'); ?>" class="btn btn-success">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </a>
                            
                        </div>

                        <!-- Tabel Produk -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">ID Produk</th>
                                        <th width="35%">Nama Produk</th>
                                        <th width="15%">Harga</th>
                                        <th width="15%">Kategori</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($produk) > 0): ?>
                                        <?php $no = 1; foreach($produk as $p): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $p->id_produk; ?></td>
                                                <td><?= $p->nama_produk; ?></td>
                                                <td>Rp <?= number_format($p->harga, 0, ',', '.'); ?></td>
                                                <td><?= $p->nama_kategori; ?></td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        <?= $p->nama_status; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('/index.php/produk/edit/'.$p->id_produk); ?>" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" 
                                                       onclick="confirmDelete(<?= $p->id_produk; ?>)" 
                                                       class="btn btn-danger btn-sm" 
                                                       title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>Belum ada data produk dengan status "bisa dijual"</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Konfirmasi Hapus dengan SweetAlert2 -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data produk akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url("/index.php/produk/hapus/"); ?>' + id;
                }
            });
        }
    </script>
</body>
</html>