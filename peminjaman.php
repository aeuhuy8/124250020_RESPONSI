<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
require_once 'koneksi.php';


if (isset($_POST['tambah'])) {
    $kode_peminjaman = $_POST['kode_peminjaman'];
    $nama_peminjam = $_POST['nama_peminjam'];
    $id_buku = $_POST['id_buku'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    
    $q = mysqli_query($conn, "SELECT * FROM buku WHERE id=$id_buku");
    $buku = mysqli_fetch_assoc($q);
    $judul_buku = $buku['judul_buku'];
    
    $query = "INSERT INTO peminjaman (kode_peminjaman, nama_peminjam, id_buku, judul_buku, tanggal_peminjaman, tanggal_pengembalian, status) 
              VALUES ('$kode_peminjaman', '$nama_peminjam', $id_buku, '$judul_buku', '$tanggal_peminjaman', '$tanggal_pengembalian', 'Dipinjam')";
    mysqli_query($conn, $query);
    
    mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id=$id_buku");
    
    header("Location: peminjaman.php");
}

if (isset($_GET['kembalikan'])) {
    $id = $_GET['kembalikan'];
    $query = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id=$id");
    $pinjam = mysqli_fetch_assoc($query);
    $id_buku = $pinjam['id_buku'];
    
    mysqli_query($conn, "UPDATE peminjaman SET status='Dikembalikan' WHERE id=$id");
    mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id=$id_buku");
    
    header("Location: peminjaman.php");
}

$data = mysqli_query($conn, "SELECT * FROM peminjaman ORDER BY id DESC");
$buku_tersedia = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman - Pustaka Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">PUSTAKA DIGITAL</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="koleksi_buku.php">Koleksi Buku</a>
                <a class="nav-link active" href="peminjaman.php">Peminjaman</a>
                <a class="nav-link text-warning" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h5 class="mb-0">📝 Data Peminjaman Buku</h5>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <i class="bi bi-plus-circle"></i> Catat Peminjaman
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Kode Pinjam</th>
                                <th>Nama Peminjam</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($data) == 0): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data peminjaman</td>
                                </tr>
                            <?php else: ?>
                                <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['kode_peminjaman'] ?></td>
                                        <td><?= $row['nama_peminjam'] ?></td>
                                        <td><?= $row['judul_buku'] ?></td>
                                        <td><?= $row['tanggal_peminjaman'] ?></td>
                                        <td><?= $row['tanggal_pengembalian'] ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'Dipinjam'): ?>
                                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Dikembalikan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] == 'Dipinjam'): ?>
                                                <a href="peminjaman.php?kembalikan=<?= $row['id'] ?>" 
                                                   class="btn btn-success btn-sm"
                                                   onclick="return confirm('Apakah buku sudah dikembalikan?')">
                                                    <i class="bi bi-arrow-return-left"></i> Kembalikan
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
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
                        <h5 class="modal-title"><i class="bi bi-journal-plus"></i> Catat Peminjaman Buku</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Peminjaman</label>
                            <input type="text" name="kode_peminjaman" class="form-control" placeholder="Contoh: PJM001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama lengkap peminjam" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Judul Buku</label>
                            <select name="id_buku" class="form-select" required>
                                <option value="">-- Pilih Buku (Stok Tersedia) --</option>
                                <?php while ($buku = mysqli_fetch_assoc($buku_tersedia)): ?>
                                    <option value="<?= $buku['id'] ?>">
                                        <?= $buku['judul_buku'] ?> (Stok: <?= $buku['stok'] ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Peminjaman</label>
                            <input type="date" name="tanggal_peminjaman" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengembalian</label>
                            <input type="date" name="tanggal_pengembalian" class="form-control" required>
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