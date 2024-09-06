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

// Mengambil daftar buku
$result_buku = mysqli_query($koneksi, "SELECT * FROM buku");

// Menambahkan ulasan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_buku = $_POST['buku'];
    $ulasan = $_POST['ulasan'];
    $rating = $_POST['rating'];
    $id_user = $_SESSION['user']['id_user']; // Mengambil ID user dari session

    // Menambahkan ulasan ke database
    $stmt = $koneksi->prepare("INSERT INTO ulasan (id_user, id_buku, ulasan, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $id_user, $id_buku, $ulasan, $rating);

    if ($stmt->execute()) {
        echo "<script>
                alert('Ulasan berhasil ditambahkan!');
                window.location.href = 'ulasan.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan ulasan, silakan coba lagi.');
              </script>";
    }

    $stmt->close();
}

// Mengambil ulasan yang sudah ada
$result_ulasan = mysqli_query($koneksi, "
    SELECT u.id_ulasan, b.judul, u.ulasan, u.rating, us.nama 
    FROM ulasan u
    JOIN buku b ON u.id_buku = b.id_buku
    JOIN user us ON u.id_user = us.id_user
");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Ulasan Buku - Perpustakaan Digital</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
    </style>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container">
        <!-- Form Ulasan -->
        <div class="card mb-5">
            <div class="card-header text-center">
                <h3 class="mb-0">Tulis Ulasan Anda</h3>
            </div>
            <div class="card-body">
                <form action="ulasan.php" method="post">
                    <div class="mb-3">
                        <label for="buku" class="form-label">Pilih Buku</label>
                        <select name="buku" id="buku" class="form-select" required>
                            <?php while ($row = mysqli_fetch_assoc($result_buku)) : ?>
                                <option value="<?php echo $row['id_buku']; ?>"><?php echo $row['judul']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ulasan" class="form-label">Ulasan Anda</label>
                        <textarea id="ulasan" name="ulasan" class="form-control" rows="4" placeholder="Tulis ulasan Anda di sini" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (1-5)</label>
                        <input type="number" id="rating" name="ratting" class="form-control" min="1" max="5" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Kirim Ulasan</button>
                </form>
            </div>
        </div>

        <!-- Daftar Ulasan -->
        <div class="card">
            <div class="card-header text-center">
                <h3>Daftar Ulasan</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Ulasan</th>
                            <th>Rating</th>
                            <th>Pengguna</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_ulasan)) : ?>
                            <tr>
                                <td><?php echo $row['judul']; ?></td>
                                <td><?php echo $row['ulasan']; ?></td>
                                <td><?php echo $row['rating']; ?></td>
                                <td><?php echo $row['nama']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>