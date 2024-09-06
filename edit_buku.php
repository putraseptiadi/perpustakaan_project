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

// Mendapatkan ID buku yang akan di-edit
if (isset($_GET['id'])) {
    $id_buku = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Debugging: Tampilkan ID Buku dan Query
    // echo "ID Buku: $id_buku<br>"; // Hapus baris ini setelah debugging selesai

    // Query untuk mengambil data buku berdasarkan ID
    $query = "SELECT * FROM buku WHERE id_buku = '$id_buku'";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die("Query Error: " . mysqli_error($koneksi));
    }

    $data = mysqli_fetch_array($result);

    // Debugging: Tampilkan Data Buku
    if ($data) {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>"; // Hapus baris ini setelah debugging selesai
    } else {
        echo "<script>alert('Buku tidak ditemukan!'); window.location.href='buku.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID buku tidak valid!'); window.location.href='buku.php';</script>";
    exit;
}

// Proses update data buku
if (isset($_POST['submit'])) {
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // Query untuk update data buku
    $query_update = "UPDATE buku SET 
                        id_kategori = '$id_kategori',
                        judul = '$judul',
                        penulis = '$penulis',
                        penerbit = '$penerbit',
                        tahun_terbit = '$tahun_terbit',
                        deskripsi = '$deskripsi'
                    WHERE id_buku = '$id_buku'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Buku berhasil diupdate!'); window.location.href='buku.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate buku! Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// Ambil daftar kategori untuk dropdown
$query_kategori = "SELECT * FROM kategori";
$result_kategori = mysqli_query($koneksi, $query_kategori);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Buku</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="id_kategori" class="form-label">Kategori</label>
                <select class="form-select" id="id_kategori" name="id_kategori" required>
                    <?php
                    while ($kategori = mysqli_fetch_array($result_kategori)) {
                        $selected = ($kategori['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
                        echo "<option value='{$kategori['id_kategori']}' $selected>{$kategori['kategori']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="judul" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" class="form-control" id="penulis" name="penulis" value="<?= htmlspecialchars($data['penulis']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= htmlspecialchars($data['penerbit']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="<?= htmlspecialchars($data['tahun_terbit']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?= htmlspecialchars($data['deskripsi']); ?></textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Update Buku</button>
            <a href="buku.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>