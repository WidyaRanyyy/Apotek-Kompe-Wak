<?php
session_start();
include 'includes/db_connect.php';

// Keamanan: Pastikan hanya pengguna yang login yang bisa akses
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data terbaru pengguna dari database
$stmt = $conn->prepare("SELECT name, email, address, phone_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Cek jika ada notifikasi sukses (dari update)
$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'updated') {
    $message = '<p style="color:green;font-weight:bold;">Profil Anda berhasil diperbarui!</p>';
}
?>

<?php include 'includes/header.php'; ?>

<h1 class="page-title">Akun Saya</h1>

<div class="account-container">
    <div class="account-sidebar">
        <a href="akun.php" class="active">Update Profil</a>
        <a href="riwayat_pesanan.php">Riwayat Pesanan</a>
    </div>

    <div class="account-content">
        <h2>Update Profil</h2>
        <p>Kelola informasi profil dan alamat Anda.</p>
        
        <?php echo $message; // Tampilkan pesan sukses jika ada ?>

        <form action="/actions/update_profile.php" method="POST">
            <div>
                <label for="name">Nama Lengkap:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <br>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <br>
            <div>
                <label for="phone_number">No. Telepon:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
            </div>
            <br>
            <div>
                <label for="address">Alamat Pengiriman:</label>
                <textarea id="address" name="address" rows="4"><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            <br>
            <button type="submit" class="button-checkout">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>