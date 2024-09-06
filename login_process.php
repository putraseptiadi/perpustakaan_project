<?php
include "koneksi.php";
session_start();

if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: home.php'); // Redirect ke halaman utama setelah login
            exit;
        } else {
            header('Location: login.php?error=1');
            exit;
        }
    } else {
        header('Location: login.php?error=1');
        exit;
    }
    $stmt->close();
}
