<?php
session_start();
include '../includes/db_connect.php';

// Keamanan: Pastikan admin yang login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die("Akses ditolak.");
}

// Ambil data dari form
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Update status di database
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        // Jika berhasil, kembalikan ke halaman detail
        header("Location: /admin/view_order.php?id=" . $order_id . "&status=updated");
    } else {
        echo "Gagal mengupdate status: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Jika data tidak lengkap, kembalikan ke dasbor admin
    header("Location: /admin/index.php");
}
exit;
?>