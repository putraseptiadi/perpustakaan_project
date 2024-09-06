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

// Proses update kategori
$message = '';
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']); // Sanitasi input

    $query = "UPDATE kategori SET kategori = '$kategori' WHERE id_kategori = $id";
    if (mysqli_query($koneksi, $query)) {
        $message = '<div class="alert alert-success">Kategori berhasil diperbarui!</div>';
    } else {
        $message = '<div class="alert alert-danger">Error: ' . mysqli_error($koneksi) . '</div>';
    }
}

// Ambil data kategori untuk di-edit
$id = $_GET['id'];
$query = "SELECT * FROM kategori WHERE id_kategori = $id";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Kategori Buku - Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />

    <style>
        /* Menggeser konten ke kiri dan ke atas */
        .container-fluid {
            margin-left: -110px;
            /* Geser konten ke kiri */
            margin-top: -25px;
            /* Geser konten ke atas */
        }
    </style>

</head>

<body class="sb-nav-fixed">
    <div id="layoutSidenav">
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Kategori Buku</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="kategori.php">Kategori</a></li>
                        <li class="breadcrumb-item active">Edit Kategori</li>
                    </ol>

                    <!-- Menampilkan pesan setelah proses edit kategori -->
                    <?php if ($message) echo $message; ?>

                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id_kategori']); ?>" />
                        <div class="form-group">
                            <label for="kategori">Nama Kategori</label>
                            <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo htmlspecialchars($row['kategori']); ?>" required />
                        </div>
                        <button type="submit" name="update" class="btn btn-success mt-3">Update Kategori</button>
                        <a href="kategori.php" class="btn btn-secondary mt-3">Kembali ke Daftar Kategori</a>
                    </form>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; Perpustakaan Digital 2024</div>
                    </div>
                </div>
            </footer>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
</body>

</html>