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

// Proses tambah buku jika form disubmit
$message = '';
if (isset($_POST['tambah'])) {
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // Query untuk menambah buku ke database
    $query = "INSERT INTO buku (id_kategori, judul, penulis, penerbit, tahun_terbit, deskripsi) 
              VALUES ('$id_kategori', '$judul', '$penulis', '$penerbit', '$tahun_terbit', '$deskripsi')";

    if (mysqli_query($koneksi, $query)) {
        $message = '<div class="alert alert-success">Buku berhasil ditambahkan!</div>';
    } else {
        $message = '<div class="alert alert-danger">Error: ' . mysqli_error($koneksi) . '</div>';
    }
}

// Ambil daftar kategori untuk dropdown
$query_kategori = "SELECT * FROM kategori";
$result_kategori = mysqli_query($koneksi, $query_kategori);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Tambah Buku - Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />

    <style>
        /* Menggeser konten ke kiri dan ke atas */
        .container-fluid {
            margin-left: -140px;
            /* Geser konten ke kiri */
            margin-top: -45px;
            /* Geser konten ke atas */
        }
    </style>
</head>

<body class="sb-nav-fixed">

    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Tambah Buku</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="buku.php">Buku</a></li>
                        <li class="breadcrumb-item active">Tambah Buku</li>
                    </ol>

                    <!-- Menampilkan pesan setelah proses tambah buku -->
                    <?php if ($message) echo $message; ?>

                    <form method="post" action="">
                        <div class="form-group mb-3">
                            <label for="id_kategori">Kategori</label>
                            <select class="form-control" id="id_kategori" name="id_kategori" required>
                                <option value="">Pilih Kategori</option>
                                <?php while ($kategori = mysqli_fetch_assoc($result_kategori)): ?>
                                    <option value="<?= $kategori['id_kategori'] ?>"><?= $kategori['kategori'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="judul">Judul Buku</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan Judul Buku" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="penulis">Penulis</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Masukkan Nama Penulis" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="penerbit">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Masukkan Nama Penerbit" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="tahun_terbit">Tahun Terbit</label>
                            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" placeholder="Masukkan Tahun Terbit" required />
                        </div>

                        <div class="form-group mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan Deskripsi Buku" required></textarea>
                        </div>

                        <button type="submit" name="tambah" class="btn btn-success">Tambah Buku</button>
                        <a href="buku.php" class="btn btn-secondary">Kembali ke Daftar Buku</a>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>