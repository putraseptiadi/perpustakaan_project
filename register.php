<?php
include "koneksi.php"; // Pastikan file koneksi sudah benar

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $username = $_POST['username'];
    $level = $_POST['level'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($nama) || empty($email) || empty($alamat) || empty($no_telepon) || empty($username) || empty($password) || empty($confirm_password) || empty($level)) {
        $error_message = 'Semua kolom harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Format email tidak valid.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Password dan konfirmasi password tidak cocok.';
    } else {
        // Cek apakah username sudah ada di database
        $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Username sudah digunakan.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Simpan pengguna baru ke database
            $stmt = $koneksi->prepare("INSERT INTO user (nama, email, alamat, no_telepon, username, password, level) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $nama, $email, $alamat, $no_telepon, $username, $hashed_password, $level);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Pendaftaran berhasil! Silakan login.');
                        window.location.href = 'login.php';
                      </script>";
                exit;
            } else {
                $error_message = 'Pendaftaran gagal, silakan coba lagi.';
            }

            $stmt->close();
        }
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
    <title>Register - Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: url('path_to_your_background_image.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 185vh;
            padding: 1rem;
        }

        .register-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            box-sizing: border-box;
        }

        .register-form img {
            max-width: 120px;
            margin-bottom: 1rem;
        }

        .form-label {
            font-weight: bold;
        }

        .form-control,
        .form-select {
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .alert {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-form text-center">
            <img src="logo.png" alt="Logo" />
            <h2 class="mb-4">Register</h2>
            <!-- Pesan Informasi -->
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php } ?>
            <form action="register.php" method="post">
                <div class="mb-3">
                    <label for="nama" class="form-label">Full Name</label>
                    <input type="text" id="nama" name="nama" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Address</label>
                    <input type="text" id="alamat" name="alamat" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">Phone Number</label>
                    <input type="tel" id="no_telepon" name="no_telepon" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="level" class="form-label">Level</label>
                    <select id="level" name="level" class="form-select" required>
                        <option value="peminjam">Peminjam</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="mt-3">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>