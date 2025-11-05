<?php include 'includes/header.php'; ?>

    <h1 class="page-title">Registrasi Akun Baru</h1>
    <p>Silakan isi form di bawah ini untuk mendaftar.</p>
    
    <form action="actions/register_process.php" method="POST">
        <div>
            <label for="name">Nama Lengkap:</label><br>
            <input type="text" id="name" name="name" required>
        </div>
        <br>
        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required>
        </div>
        <br>
        <div>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a>.</p>

<?php include 'includes/footer.php'; ?>