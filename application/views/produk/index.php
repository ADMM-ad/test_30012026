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
   <div class="container-fluid mt-2 px-1">
    <div class="row mx-1">
        <div class="col-12 px-1">
                <div class="card shadow">
                    
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-box"></i> Data Produk - Bisa Dijual
                        </h5>
                        <a href="<?= base_url('index.php/produk/tambah'); ?>" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
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

                        <!-- Form Pencarian dan Filter (Center) -->
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="searchInput" 
                                           placeholder="Cari nama produk...">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="filterKategori">
                                    <option value="">Semua Kategori</option>
                                    <?php 
                                    // Ambil unique kategori dari data produk
                                    $kategori_list = [];
                                    foreach($produk as $p) {
                                        if(!in_array($p->nama_kategori, $kategori_list)) {
                                            $kategori_list[] = $p->nama_kategori;
                                        }
                                    }
                                    foreach($kategori_list as $kat): 
                                    ?>
                                        <option value="<?= $kat; ?>"><?= $kat; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-secondary w-100" onclick="resetFilter()">
                                    <i class="fas fa-redo"></i> 
                                </button>
                            </div>
                        </div>

                        <!-- Tabel Produk -->
                        <div class="table-responsive">
                            <table class="table table-bordered  table-hover" id="tableProduk">
                                <thead>
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
                                                <td class="nama-produk"><?= $p->nama_produk; ?></td>
                                                <td>Rp <?= number_format($p->harga, 0, ',', '.'); ?></td>
                                                <td class="kategori"><?= $p->nama_kategori; ?></td>
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
                                        <tr id="emptyRow">
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

        // Filter dan Search
        const searchInput = document.getElementById('searchInput');
        const filterKategori = document.getElementById('filterKategori');
        const tableRows = document.querySelectorAll('#tableProduk tbody tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedKategori = filterKategori.value.toLowerCase();

            let visibleCount = 0;

            tableRows.forEach(row => {
                // Skip empty row
                if(row.id === 'emptyRow') return;

                const namaProduk = row.querySelector('.nama-produk')?.textContent.toLowerCase() || '';
                const kategori = row.querySelector('.kategori')?.textContent.toLowerCase() || '';

                const matchSearch = namaProduk.includes(searchTerm);
                const matchKategori = selectedKategori === '' || kategori.includes(selectedKategori);

                if (matchSearch && matchKategori) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update nomor urut
            let nomor = 1;
            tableRows.forEach(row => {
                if(row.style.display !== 'none' && row.id !== 'emptyRow') {
                    row.cells[0].textContent = nomor++;
                }
            });
        }

        function resetFilter() {
            searchInput.value = '';
            filterKategori.value = '';
            filterTable();
        }

        // Event listeners
        searchInput.addEventListener('keyup', filterTable);
        filterKategori.addEventListener('change', filterTable);
    </script>
</body>
</html>