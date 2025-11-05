<?php
session_start(); // Wajib untuk mengelola sesi login
include '../includes/db_connect.php';

$email = $_POST['email'];
$password_input = $_POST['password'];

// Siapkan query untuk mengambil data user
$stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // User ditemukan, ambil datanya
    $user = $result->fetch_assoc();
    
    // Verifikasi password yang di-input dengan hash di database
    if (password_verify($password_input, $user['password'])) {
        // Password cocok! Buat sesi login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        // Arahkan ke halaman utama (atau halaman akun)
        header("Location: ../index.php");
        exit;
    } else {
        // Password salah
        echo "Password salah. <a href='../login.php'>Coba lagi</a>.";
    }
} else {
    // Email tidak ditemukan
    echo "Email tidak terdaftar. <a href='../login.php'>Coba lagi</a>.";
}

$stmt->close();
$conn->close();
?>