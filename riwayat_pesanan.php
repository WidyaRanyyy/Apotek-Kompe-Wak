<?php
session_start();
include 'includes/db_connect.php';

// Keamanan: Pastikan hanya pengguna yang login yang bisa akses
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua pesanan milik pengguna ini, diurutkan dari yang terbaru
$stmt = $conn->prepare("SELECT id, order_date, total_amount, status FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>

<?php include 'includes/header.php'; ?>

<h1 class="page-title">Akun Saya</h1>

<div class="account-container">
    <div class="account-sidebar">
        <a href="akun.php">Update Profil</a>
        <a href="riwayat_pesanan.php" class="active">Riwayat Pesanan</a>
    </div>

    <div class="account-content">
        <h2>Riwayat Pesanan Saya</h2>
        <p>Lihat semua transaksi yang pernah Anda lakukan.</p>
        
        <div class="admin-table-container"> 
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Tanggal Pesan</th>
                    <th>Total Belanja</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                
                <?php if ($orders->num_rows > 0): ?>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td>Rp <?php echo number_format($row['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><a href="detail_pesanan.php?id=<?php echo $row['id']; ?>">Detail</a></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Anda belum memiliki riwayat pesanan.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>