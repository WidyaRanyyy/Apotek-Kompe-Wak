<?php
session_start();
include '../includes/db_connect.php';

// Keamanan: Pastikan admin yang login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: /login.php");
    exit;
}

// 1. Ambil Order ID dari URL
if (!isset($_GET['id'])) {
    header("Location: /admin/index.php");
    exit;
}
$order_id = $_GET['id'];

// 2. Ambil Info Pesanan Utama (dan data pelanggan)
$sql_order = "SELECT orders.*, users.name, users.email, users.address, users.phone_number 
              FROM orders 
              JOIN users ON orders.user_id = users.id 
              WHERE orders.id = ?";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$order = $stmt_order->get_result()->fetch_assoc();

if (!$order) {
    echo "Pesanan tidak ditemukan.";
    exit;
}

// 3. Ambil Item Produk dalam Pesanan tersebut
$sql_items = "SELECT products.name, products.image_url, order_items.quantity, order_items.price_per_item 
              FROM order_items 
              JOIN products ON order_items.product_id = products.id 
              WHERE order_items.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>

<?php include '../includes/header.php'; ?>

<h1 class="page-title">Detail Pesanan #<?php echo $order['id']; ?></h1>

<div class="order-detail-container">
    
    <h3>Informasi Pelanggan</h3>
    <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
    <p><strong>Alamat:</strong> <?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
    <p><strong>No. Telepon:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>

    <h3>Informasi Pesanan</h3>
    <p><strong>Status Saat Ini:</strong> <strong><?php echo htmlspecialchars($order['status']); ?></strong></p>
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
    
    <hr>
    <h3>Ubah Status Pesanan</h3>
    <form action="/actions/update_order_status.php" method="POST">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <label for="status">Status Baru:</label>
        <select name="status" id="status">
            <option value="Pending" <?php if($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Processing" <?php if($order['status'] == 'Processing') echo 'selected'; ?>>Processing (Diproses)</option>
            <option value="Shipped" <?php if($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped (Dikirim)</option>
            <option value="Completed" <?php if($order['status'] == 'Completed') echo 'selected'; ?>>Completed (Selesai)</option>
            <option value="Cancelled" <?php if($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled (Dibatalkan)</option>
        </select>
        <button type="submit" class="button-update">Update Status</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>