<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require_once 'koneksi.php';

if (isset($_POST['tambah'])) {
    $kode_buku = $_POST['kode_buku'];
    $judul_buku = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    
    $query = "INSERT INTO buku (kode_buku, judul_buku, pengarang, kategori, stok) 
              VALUES ('$kode_buku', '$judul_buku', '$pengarang', '$kategori', $stok)";
    mysqli_query($conn, $query);
    header("Location: koleksi_buku.php");
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $kode_buku = $_POST['kode_buku'];
    $judul_buku = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    
    $query = "UPDATE buku SET kode_buku='$kode_buku', judul_buku='$judul_buku', 
              pengarang='$pengarang', kategori='$kategori', stok=$stok WHERE id=$id";
    mysqli_query($conn, $query);
    header("Location: koleksi_buku.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM buku WHERE id=$id");
    header("Location: koleksi_buku.php");
}

$data = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Koleksi Buku - Pustaka Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">PUSTAKA DIGITAL</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="koleksi_buku.php">Koleksi Buku</a>
                <a class="nav-link" href="peminjaman.php">Peminjaman</a>
                <a class="nav-link text-warning" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h5 class="mb-0">Daftar Koleksi Buku</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <i class="bi bi-plus-circle"></i> Tambah Buku
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Kode Buku</th>
                                <th>Judul Buku</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($data) == 0): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data buku</td>
                                </tr>
                            <?php else: ?>
                                <?php while ($row = mysqli_fetch_assoc($data)): 
                                    if ($row['stok'] == 0) {
                                        $status = '<span class="badge bg-danger">Habis</span>';
                                    } elseif ($row['stok'] <= 5) {
                                        $status = '<span class="badge bg-warning text-dark">Menipis</span>';
                                    } else {
                                        $status = '<span class="badge bg-success">Tersedia</span>';
                                    }
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['kode_buku'] ?></td>
                                        <td><?= $row['judul_buku'] ?></td>
                                        <td><?= $row['pengarang'] ?></td>
                                        <td><?= $row['kategori'] ?></td>
                                        <td><?= $row['stok'] ?></td>
                                        <td><?= $status ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <a href="koleksi_buku.php?hapus=<?= $row['id'] ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus buku <?= $row['judul_buku'] ?>?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Buku</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Kode Buku</label>
                                                            <input type="text" name="kode_buku" class="form-control" value="<?= $row['kode_buku'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Judul Buku</label>
                                                            <input type="text" name="judul_buku" class="form-control" value="<?= $row['judul_buku'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Pengarang</label>
                                                            <input type="text" name="pengarang" class="form-control" value="<?= $row['pengarang'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Kategori</label>
                                                            <input type="text" name="kategori" class="form-control" value="<?= $row['kategori'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Stok</label>
                                                            <input type="number" name="stok" class="form-control" value="<?= $row['stok'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="bi bi-journal-plus"></i> Tambah Buku Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Buku</label>
                            <input type="text" name="kode_buku" class="form-control" placeholder="Contoh: BK001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul_buku" class="form-control" placeholder="Judul lengkap buku" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pengarang</label>
                            <input type="text" name="pengarang" class="form-control" placeholder="Nama pengarang" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control" placeholder="Contoh: Fiksi,Petualangan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" placeholder="Jumlah stok" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>