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

// Proses peminjaman
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_buku = $_POST['buku'];
    $tanggal_peminjaman = $_POST['tanggal_pinjam'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'] ?: null; // Kosongkan jika tidak diisi
    $id_user = $_SESSION['user']['id_user']; // Mengambil ID user dari session

    // Menambahkan peminjaman ke database
    $stmt = $koneksi->prepare("INSERT INTO peminjaman (id_user, id_buku, tanggal_peminjaman, tanggal_pengembalian, status_peminjaman) VALUES (?, ?, ?, ?, 'Dipinjam')");
    $stmt->bind_param("iiss", $id_user, $id_buku, $tanggal_peminjaman, $tanggal_pengembalian);

    if ($stmt->execute()) {
        echo "<script>
                alert('Peminjaman berhasil!');
                window.location.href = 'peminjaman.php';
              </script>";
    } else {
        echo "<script>
                alert('Peminjaman gagal, silakan coba lagi.');
              </script>";
    }

    $stmt->close();
}
