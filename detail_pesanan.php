<?php
session_start();
include 'includes/db_connect.php';

// Keamanan 1: Pastikan login
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

// Keamanan 2 (KRUSIAL):
// Ambil info pesanan HANYA JIKA ID pesanan cocok DAN ID pengguna cocok.
$sql_order = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("ii", $order_id, $user_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

// Jika query tidak menemukan apa-apa (pesanan bukan miliknya, atau tidak ada)
if (!$order) {
    echo "Pesanan tidak ditemukan atau Anda tidak memiliki akses.";
    exit;
}

// 3. Ambil Item Produk dalam Pesanan tersebut
$sql_items = "SELECT products.name, order_items.quantity, order_items.price_per_item 
              FROM order_items 
              JOIN products ON order_items.product_id = products.id 
              WHERE order_items.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>

<?php include 'includes/header.php'; ?>

<h1 class="page-title">Detail Pesanan #<?php echo $order['id']; ?></h1>

<div class="order-detail-container">
    
    <h3>Informasi Pesanan</h3>
    <p><strong>Status:</strong> <strong><?php echo htmlspecialchars($order['status']); ?></strong></p>
    <p><strong>Total Belanja:</strong> Rp <?php echo number_format($order['total_amount']); ?></p>
    <p><strong>Tanggal Pesan:</strong> <?php echo $order['order_date']; ?></p>
    
    <h3>Item yang Dipesan</h3>
    <table>
        <tr>
            <th>Produk</th>
            <th>Kuantitas</th>
            <th>Harga Satuan</th>
            <th>Total</th>
        </tr>
        <?php while ($item = $items->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>Rp <?php echo number_format($item['price_per_item']); ?></td>
            <td>Rp <?php echo number_format($item['price_per_item'] * $item['quantity']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="riwayat_pesanan.php">← Kembali ke Riwayat Pesanan</a>
</div>

<?php include '../includes/footer.php'; ?>