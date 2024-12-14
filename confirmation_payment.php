<?php
        session_start(); // Pastikan session dimulai sebelum kode lainnya
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart-data'])) {
            // Simpan data keranjang ke session setelah form disubmit
            $_SESSION['cart'] = json_decode($_POST['cart-data'], true); // Simpan data keranjang ke session
            header('Location: confirmation_payment.php'); // Redirect ke halaman konfirmasi
            exit();
        }
        ?>
        <?php
        if (isset($_SESSION['cart'])) {
        } else {
            echo "No cart data in session.";
        }
        $cart = $_SESSION['cart'] ?? []; // Gunakan data session atau array kosong jika tidak ada
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Order Confirmation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .hover-bg-red-500:hover {
            background-color: #ef4444; /* Hover effect */
        }
        .click-bg-red-500:active {
            background-color: #ef4444; /* Active effect */
        }
        .payment-button {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .payment-button.active {
            background-color: #ef4444;
            transform: scale(1.05); /* Efek tombol sedikit membesar */
        }
    </style>
    <script>
        function toggleActive(button) {
            // Ambil semua tombol pembayaran
            const buttons = document.querySelectorAll('.payment-button');
            
            // Hapus kelas 'active' dari semua tombol dan toggle status tombol yang dipilih
            buttons.forEach(btn => btn.classList.remove('active', 'bg-red-500', 'bg-gray-600'));
            
            // Tambahkan kelas 'active' dan 'bg-red-500' untuk tombol yang dipilih
            button.classList.add('active', 'bg-red-500');
            
            // Simpan metode pembayaran ke input hidden
            document.getElementById('payment-method').value = button.innerText.trim();
        }
    </script>
</head>
<body class="bg-gray-900 text-white font-sans">
    <div class="flex flex-col md:flex-row justify-center items-center min-h-screen p-4 space-y-4 md:space-y-0 md:space-x-4">
        <!-- Confirmation Section -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full md:w-1/2 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold">Confirmation</h2>
                </div>
                <p class="text-gray-400 mb-4">Orders #00001</p>
                <ul class="space-y-4">
                    <?php if (empty($cart)): ?>
                        <li class="text-center text-gray-400">Keranjang kosong, mohon pilih pesanan</li>
                    <?php else: ?>
                        <?php foreach ($cart as $item): ?>
                            <li>
                                <div class="flex justify-between">
                                    <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                                    <span>Rp<?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="mt-6">
                <div class="flex justify-between text-gray-400">
                    <p>Discount</p>
                    <p>Rp 0</p>
                </div>
                <div class="flex justify-between text-white font-semibold mt-2">
                    <p>Sub total</p>
                    <p>Rp<?php echo number_format($totalPrice, 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>
        <!-- Payment Section -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full md:w-1/2 flex flex-col justify-between">
            <form method="POST" action="">
                <div>
                    <h2 class="text-xl font-semibold mb-6">Payment</h2>
                    <p class="text-gray-400 mb-4">2 payment methods available</p>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Payment Method</h3>
                        <div class="flex space-x-4">
                            <button type="button" class="bg-gray-600 text-white p-4 rounded-lg flex-1 flex items-center justify-center hover-bg-red-500 click-bg-red-500 payment-button" onclick="toggleActive(this)">
                                <i class="fas fa-money-bill-wave mr-2"></i>Cash
                            </button>
                            <button type="button" class="bg-gray-600 text-white p-4 rounded-lg flex-1 flex items-center justify-center hover-bg-red-500 click-bg-red-500 payment-button" onclick="toggleActive(this)">
                                <i class="fas fa-qrcode mr-2"></i>QR Code
                            </button>
                        </div>
                        <input type="hidden" id="payment-method" name="payment-method" value="">
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-400 mb-2" for="customer-name">Customer Name</label>
                            <input class="w-full p-3 bg-gray-700 text-white rounded-lg" id="customer-name" name="customer-name" placeholder="Name here" type="text" value="<?php echo $_SESSION['customer_name'] ?? ''; ?>" required/>
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2" for="phone-number">Phone Number</label>
                            <input class="w-full p-3 bg-gray-700 text-white rounded-lg" id="phone-number" name="phone-number" placeholder="Phone number" type="tel" pattern="\d*" value="<?php echo $_SESSION['phone_number'] ?? ''; ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')"/>
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2" for="order-type">Order Type</label>
                            <select class="w-full p-3 bg-gray-700 text-white rounded-lg" id="order-type" name="order-type">
                                <option <?php echo (isset($_SESSION['order_type']) && $_SESSION['order_type'] == 'Dine In') ? 'selected' : ''; ?>>Dine In</option>
                                <option <?php echo (isset($_SESSION['order_type']) && $_SESSION['order_type'] == 'Take Away') ? 'selected' : ''; ?>>Take Away</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-6">
                    <button type="button" class="bg-gray-600 text-white p-4 rounded-lg flex-1 mr-2" onclick="window.location.href='uus.php'">Cancel</button>
                    <button type="submit" class="bg-gray-600 text-white p-4 rounded-lg flex-1 ml-2" onclick="window.location.href='struk.php'">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>