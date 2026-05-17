<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pustaka Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">PUSTAKA DIGITAL</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="koleksi_buku.php">Koleksi Buku</a>
                <a class="nav-link" href="peminjaman.php">Peminjaman</a>
                <a class="nav-link text-warning" href="logout.php">Logout (<?= $_SESSION['user']['username'] ?>)</a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body text-center py-5">
                <h2>Selamat Datang, <?= $_SESSION['user']['username'] ?>!</h2>
                <p class="lead">Silakan pilih menu di atas untuk mengelola perpustakaan.</p>
                <hr>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h3>Koleksi Buku</h3>
                                <p>Kelola data buku (Tambah, Edit, Hapus)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h3>Peminjaman</h3>
                                <p>Catat peminjaman & konfirmasi pengembalian</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>