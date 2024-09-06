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

// Ambil daftar buku
$result = mysqli_query($koneksi, "SELECT * FROM buku");
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
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Formulir Peminjaman Buku</h1>

        <div class="card">
            <div class="card-body">
                <form action="proses_peminjaman.php" method="post">
                    <div class="mb-3">
                        <label for="buku" class="form-label">Pilih Buku:</label>
                        <select name="buku" id="buku" class="form-select" required>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <option value="<?php echo $row['id_buku']; ?>"><?php echo $row['judul']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam:</label>
                        <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian:</label>
                        <input type="date" id="tanggal_pengembalian" name="tanggal_pengembalian" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Pinjam Buku</button>
                </form>
            </div>
        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>