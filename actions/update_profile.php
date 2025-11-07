<?php
session_start();
include '../includes/db_connect.php';

// Keamanan: Pastikan pengguna login
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak.");
}

$user_id = $_SESSION['user_id'];

// Ambil data dari form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Update data di database
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone_number = ?, address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $phone_number, $address, $user_id);
    
    if ($stmt->execute()) {
        // Jika berhasil, update juga session nama (jika nama diganti)
        $_SESSION['user_name'] = $name;
        
        // Kembalikan ke halaman akun dengan pesan sukses
        header("Location: /akun.php?status=updated");
    } else {
        echo "Gagal mengupdate profil: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Jika diakses langsung, kembalikan ke home
    header("Location: /index.php");
}
exit;
?>