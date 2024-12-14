<?php
session_start();
// Ambil data yang ada di session
$paymentMethod = $_SESSION['payment_method'] ?? 'Not selected';
$customerName = $_SESSION['customer_name'] ?? 'Not provided';
$phoneNumber = $_SESSION['phone_number'] ?? 'Not provided';
$orderType = $_SESSION['order_type'] ?? 'Not selected';
$cart = $_SESSION['cart'] ?? [];
$totalPrice = 0;
foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-900 text-white font-sans">
    <div class="flex flex-col justify-center items-center min-h-screen p-4">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-full md:w-1/2">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-semibold">Receipt</h2>
                <p class="text-gray-400">Order #00001</p>
            </div>
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Customer Information</h3>
                <p class="text-gray-400">Name: <?php echo htmlspecialchars($customerName); ?></p>
                <p class="text-gray-400">Phone: <?php echo htmlspecialchars($phoneNumber); ?></p>
                <p class="text-gray-400">Order Type: <?php echo htmlspecialchars($orderType); ?></p>
                <p class="text-gray-400">Payment Method: <?php echo htmlspecialchars($paymentMethod); ?></p>
            </div>
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Order Details</h3>
                <ul class="space-y-4">
                    <?php if (empty($cart)): ?>
                        <li class="text-center text-gray-400">Keranjang kosong, mohon pilih pesanan</li>
                    <?php else: ?>
                        <?php foreach ($cart as $item): ?>
                            <li>
                                <div class="flex justify-between">
                                    <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                                    <span>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
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
                    <p>Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></p>
                </div>
            </div>
            <div class="mt-6 text-center">
                <p class="text-gray-400">Thank you for your order!</p>
            </div>
        </div>
    </div>
</body>
</html>
