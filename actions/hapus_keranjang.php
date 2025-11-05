<?php
session_start();

// Periksa apakah ada product_id yang dikirim
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Periksa apakah keranjang ada dan produk tersebut ada di dalam keranjang
    if (isset($_SESSION['cart']) && array_key_exists($product_id, $_SESSION['cart'])) {
        // Hapus produk dari array session
        unset($_SESSION['cart'][$product_id]);
    }
}

// Setelah menghapus, arahkan pengguna kembali ke halaman keranjang
header('Location: ../keranjang.php');
exit;
?>