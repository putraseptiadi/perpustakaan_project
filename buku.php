<?php
include 'koneksi.php'; // Pastikan file koneksi.php sudah terhubung dengan database

// Periksa apakah sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['hapus'])) {
    $id_buku = mysqli_real_escape_string($koneksi, $_GET['hapus']);

    // Hapus data di tabel ulasan terkait dengan buku ini
    $query_hapus_ulasan = "DELETE FROM ulasan WHERE id_buku = '$id_buku'";
    mysqli_query($koneksi, $query_hapus_ulasan);

    // Hapus data di tabel peminjaman terkait dengan buku ini
    $query_hapus_peminjaman = "DELETE FROM peminjaman WHERE id_buku = '$id_buku'";
    mysqli_query($koneksi, $query_hapus_peminjaman);

    // Hapus data di tabel buku
    $query_hapus_buku = "DELETE FROM buku WHERE id_buku = '$id_buku'";

    if (mysqli_query($koneksi, $query_hapus_buku)) {
        echo "<script>alert('Buku dan data terkait berhasil dihapus!'); window.location.href='buku.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus buku!');</script>";
    }
}

// Menampilkan pesan setelah operasi hapus
$pesan = '';
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'hapus_sukses') {
        $pesan = '<div class="alert alert-success">Buku berhasil dihapus!</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid px-4">
        <h1 class="mt-4">Daftar Buku</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Buku</li>
        </ol>

        <!-- Menampilkan pesan jika ada -->
        <?php if ($pesan) echo $pesan; ?>

        <div class="d-flex justify-content-between mb-3">
            <a href="tambah_buku.php" class="btn btn-primary">Tambah Buku</a>
        </div>

        <!-- Tabel Buku -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Tahun Terbit</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT buku.*, kategori.kategori FROM buku 
                          JOIN kategori ON buku.id_kategori = kategori.id_kategori";
                $result = mysqli_query($koneksi, $query);
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['judul']}</td>
                        <td>{$row['kategori']}</td>
                        <td>{$row['penulis']}</td>
                        <td>{$row['penerbit']}</td>
                        <td>{$row['tahun_terbit']}</td>
                        <td>{$row['deskripsi']}</td>
                        <td>
                            <a href='edit_buku.php?id={$row['id_buku']}' class='btn btn-warning btn-sm'>Edit</a>
                          <a href='buku.php?hapus={$row['id_buku']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus buku ini?\")'>Hapus</a>

                        </td>
                    </tr>";
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>