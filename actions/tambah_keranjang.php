<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // LOGIKA BARU UNTUK KUANTITAS
    // Periksa apakah produk sudah ada di keranjang
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Jika sudah ada, tambahkan kuantitasnya
        $_SESSION['cart'][$product_id]++;
    } else {
        // Jika belum ada, set kuantitasnya menjadi 1
        $_SESSION['cart'][$product_id] = 1;
    }

    // Hitung total item (bukan total jenis produk)
    $total_items = 0;
    foreach ($_SESSION['cart'] as $quantity) {
        $total_items += $quantity;
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Produk berhasil ditambahkan!',
        'cart_count' => $total_items // Kirim total kuantitas
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Product ID tidak ditemukan.'
    ]);
}
?>