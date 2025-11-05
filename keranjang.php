<?php
session_start(); // Mulai session untuk membaca data keranjang
include 'includes/header.php';
include 'includes/db_connect.php'; // Hubungkan ke database
?>

<h1 class="page-title">Keranjang Belanja Anda</h1>

<div class="cart-container">
    <?php
    // Periksa apakah keranjang ada dan tidak kosong
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        
        // Ambil semua product_id dari session
        $product_ids = $_SESSION['cart'];
        
        // Ubah array of IDs menjadi string yang dipisahkan koma untuk query SQL (e.g., '1,3,5')
        $ids_string = implode(',', array_map('intval', $product_ids));
        
        // Query untuk mengambil detail produk berdasarkan ID di keranjang
        $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($ids_string)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<tr><th>Gambar</th><th>Produk</th><th>Harga</th></tr>';
            $total_price = 0;
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td><img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" width="50"></td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>Rp ' . number_format($row['price']) . '</td>';
                echo '</tr>';
                $total_price += $row['price'];
            }
            echo '</table>';

            echo '<h3>Total Belanja: Rp ' . number_format($total_price) . '</h3>';

        } else {
            echo "<p>Produk di keranjang tidak ditemukan di database.</p>";
        }

    } else {
        // Jika keranjang kosong
        echo "<p>Keranjang belanja Anda masih kosong.</p>";
    }
    ?>
</div>

<?php include 'includes/footer.php'; ?>