<?php
include '../includes/db_connect.php'; // Hubungkan ke database

// Ambil data dari form
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// VALIDASI SEDERHANA
if (empty($name) || empty($email) || empty($password)) {
    die("Error: Semua field harus diisi.");
}

// KEAMANAN: HASH PASSWORD!
// Jangan pernah simpan password sebagai teks biasa.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Siapkan query untuk mencegah SQL Injection
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
// 'sss' berarti tiga variabel berikutnya adalah string
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    // Jika berhasil
    echo "Registrasi berhasil! Silakan <a href='../login.php'>login</a>.";
} else {
    // Jika gagal (misal: email sudah terdaftar)
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>