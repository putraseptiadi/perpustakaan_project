<?php
include "koneksi.php";

// Periksa apakah sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
include "navbar.php";

// Proses Hapus Kategori jika ada permintaan penghapusan
// Proses Hapus Kategori jika ada permintaan penghapusan
if (isset($_GET['hapus'])) {
    $id_kategori = mysqli_real_escape_string($koneksi, $_GET['hapus']);

    // Hapus buku terkait dengan kategori ini
    $query_hapus_buku = "DELETE FROM buku WHERE id_kategori = '$id_kategori'";
    mysqli_query($koneksi, $query_hapus_buku);

    // Query untuk menghapus data kategori berdasarkan ID
    $query_hapus = "DELETE FROM kategori WHERE id_kategori = '$id_kategori'";

    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Kategori berhasil dihapus!'); window.location.href='kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus kategori!');</script>";
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
    <title>Perpustakaan Digital - Kategori Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Kategori Buku</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Kategori</li>
        </ol>

        <a href="kategori_tambah.php" class="btn btn-success mb-4">Tambah Kategori</a>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Daftar Kategori
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col" class="text-center">Nama Kategori</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query untuk mengambil semua data dari tabel kategori
                        $query = mysqli_query($koneksi, "SELECT * FROM kategori");

                        // Inisialisasi nomor urut
                        $no = 1;

                        // Loop untuk mengambil setiap baris data dari hasil query
                        while ($data = mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="text-center"><?= htmlspecialchars($data['kategori']); ?></td>
                                <td class="text-center">
                                    <a href="edit_kategori.php?id=<?= $data['id_kategori']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="kategori.php?hapus=<?= $data['id_kategori']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">&copy; Perpustakaan Digital 2024</div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>