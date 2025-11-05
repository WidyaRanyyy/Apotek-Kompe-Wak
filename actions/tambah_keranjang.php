<?php
// Mulai session untuk menyimpan data keranjang
session_start();

// Jika belum ada keranjang, buat keranjang sebagai array kosong
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Ambil product_id yang dikirim dari JavaScript
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Untuk sekarang, kita hanya akan menambahkan product_id ke dalam session.
    // Logika yang lebih kompleks (seperti menambah kuantitas) bisa ditambahkan nanti.
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }

    // Kirim response kembali ke JavaScript dalam format JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Produk berhasil ditambahkan ke keranjang!',
        'cart_count' => count($_SESSION['cart'])
    ]);
} else {
    // Jika tidak ada product_id, kirim response error
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Product ID tidak ditemukan.'
    ]);
}
?>