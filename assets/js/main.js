document.addEventListener('DOMContentLoaded', function() {
    
    // Cari semua tombol dengan atribut 'data-product-id'
    const addToCartButtons = document.querySelectorAll('button[data-product-id]');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            
            // Kirim data ke backend menggunakan Fetch API
            fetch('actions/tambah_keranjang.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Beri notifikasi ke pengguna
                    alert(data.message);
                    
                    // (Opsional) Update angka di ikon keranjang jika ada
                    // const cartCountElement = document.getElementById('cart-count');
                    // if(cartCountElement) {
                    //     cartCountElement.textContent = data.cart_count;
                    // }
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

});