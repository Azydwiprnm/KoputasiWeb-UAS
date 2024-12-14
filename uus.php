<?php
session_start(); // Pastikan session dimulai sebelum kode lainnya
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart-data'])) {
    $_SESSION['cart'] = json_decode($_POST['cart-data'], true); // Menyimpan data keranjang ke session
    header('Location: confirmation_payment.php'); // Redirect ke halaman konfirmasi
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>WarkopGIA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #1a1a2e;
            color: #fff;
            overflow: hidden; /* Prevent scrolling */
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 5%;
            background-color: #0f0f1a;
            padding: 10px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            position: fixed; /* Make sidebar fixed */
            height: 100%;
        }
        .sidebar .menu-item {
            margin: 20px 0;
            font-size: 24px;
            color: #e94560;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .main-content {
            width: 60%; /* Adjusted width to fit the screen */
            padding: 20px;
            margin-left: 5%; /* Adjust for fixed sidebar */
            overflow-y: auto; /* Allow scrolling for main content */
        }
        .header {
            display: flex;
            justify-content: flex-start; /* Align items to the start */
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-left: 20px; /* Align with menu category */
        }
        .header .search-bar {
            background-color: #2a2a3e;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            margin-left: 20px; /* Shift search box to the right */
        }
        .header .search-bar input {
            background: none;
            border: none;
            color: #fff;
            margin-left: 10px;
            outline: none;
        }
        .menu-category {
            display: flex;
            justify-content: flex-start; /* Align items to the start */
            margin-bottom: 20px;
            margin-left: 20px; /* Shift menu category to the right */
            flex-wrap: wrap; /* Make buttons wrap on smaller screens */
        }
        .menu-category button {
            background-color: #2a2a3e;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            margin-right: 10px; /* Add space between buttons */
            display: flex;
            align-items: center;
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }
        .menu-category button:hover, .menu-category button.active {
            background-color: #e94560; /* Change background color on hover and active */
        }
        .menu-category button i {
            margin-right: 5px; /* Add space between icon and text */
        }
        .order-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 27px; /* Further reduced gap between items */
            margin-left: 20px; /* Align with menu category */
            margin-right: 400px;
        }
        .order-item {
            background-color: #2a2a3e;
            padding: 10px; /* Reduced padding */
            border-radius: 10px;
            text-align: center;
            height: 150px; /* Increased height */
            width: 150px; /* Increased width */
        }
        .order-item img {
            width: 100%;
            height: 80px; /* Increased height */
            background-color: #1a1a2e;
            border-radius: 10px;
            margin-bottom: 5px; /* Reduced margin */
        }
        .order-item h3 {
            margin: 5px 0; /* Reduced margin */
            font-size: 14px; /* Increased font size */
        }
        .order-item p {
            color: #e94560;
            font-size: 12px; /* Increased font size */
            display: inline-block; /* Align with button */
        }
        .order-item .add-btn {
            background-color: #e94560;
            border: none;
            padding: 2px; /* Reduced padding */
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            display: inline-block; /* Align with price */
            margin-left: 5px; /* Add space between price and button */
            font-size: 10px; /* Reduced font size */
        }
        .order-summary {
            width: 30%;
            background-color: #0f0f1a; /* Changed background color to black */
            padding: 20px;
            position: fixed; /* Make order summary fixed */
            right: 0;
            top: 0;
            height: 100%;
            overflow-y: auto; /* Allow scrolling for order summary */
        }
        .order-summary h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .order-summary .order-type {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .order-summary .order-type button {
            background-color: #e94560;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }
        .order-summary .order-type button.nonactive {
            background-color: #1a1a2e;
        }
        .order-summary .order-list {
            margin-bottom: 20px;
        }
        .order-summary .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            background-color: #1a1a2e;
            padding: 10px;
            border-radius: 5px;
        }
        .order-summary .order-item img {
            width: 50px;
            height: 50px;
            background-color: #1a1a2e;
            border-radius: 10px;
        }
        .order-summary .order-item .item-details {
            flex: 1;
            margin-left: 10px;
        }
        .order-summary .order-item .item-details input {
            background: none;
            border: none;
            color: #fff;
            outline: none;
            width: 100%;
        }
        .order-summary .order-item .item-details p {
            margin: 5px 0;
        }
        .order-summary .order-item .item-actions {
            display: flex;
            align-items: center;
        }
        .order-summary .order-item .item-actions input {
            width: 40px;
            text-align: center;
            margin: 0 10px;
        }
        .order-summary .order-item .item-actions button {
            background-color: #e94560;
            border: none;
            padding: 10px;
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
        }
        .order-summary .order-total {
            margin-bottom: 20px;
        }
        .order-summary .order-total p {
            display: flex;
            justify-content: space-between;
        }
        .order-summary .order-total p span {
            color: #e94560;
        }
        .order-summary .payment-btn {
            background-color: #e94560;
            border: none;
            padding: 15px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            width: 100%;
        }
        .order-summary .cancel-btn {
            background-color: #e94560;
            border: none;
            padding: 8px;
            border-radius: 20%;
            color: #fff;
            cursor: pointer;
            margin-left: 10px;
            margin-top:20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div>
                <div class="menu-item">
                    <i class="fas fa-store"></i>
                </div>
                <div class="menu-item" style="margin-top: 25px;">
                    <i class="fas fa-calendar" style="margin-left: 0;"></i>
                </div>
                <div class="menu-item">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="menu-item">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
            <div class="menu-item" style="margin-bottom: 60px;">
                <i class="fas fa-sign-out-alt"></i>
            </div>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>WarkopGIA</h1>
                <div class="search-bar" style="margin-left: 20px;">
                    <i class="fas fa-search"></i>
                    <input placeholder="Search menu here..." type="text" onkeyup="searchMenu(this.value)"/>
                </div>
            </div>
            <div class="menu-category">
                <button onclick="filterMenu('all')" id="all-btn">
                    <i class="fas fa-th-large"></i>
                    All
                </button>
                <button onclick="filterMenu('promo')" id="promo-btn">
                    <i class="fas fa-tags"></i>
                    Promo
                </button>
                <button onclick="filterMenu('foods')" id="foods-btn">
                    <i class="fas fa-utensils"></i>
                    Foods
                </button>
                <button onclick="filterMenu('snacks')" id="snacks-btn">
                    <i class="fas fa-cookie-bite"></i>
                    Snacks
                </button>
                <button onclick="filterMenu('drinks')" id="drinks-btn">
                    <i class="fas fa-coffee"></i>
                    Drinks
                </button>
            </div>
            <div class="order-grid" id="order-grid">
                <div class="order-item foods">
                    <img alt="A plate of Mie Goreng, a popular Indonesian fried noodle dish" height="80" src="https://storage.googleapis.com/a1aa/image/eGBBuIchv4W6EqHEmq8sCOYPIfIT89DC6xl0R5iC8CaLQt3TA.jpg" width="150"/>
                    <h3>Mie Goreng</h3>
                    <p>Rp 25.000
                    <button class="add-btn" onclick="addToCart('Mie Goreng', 25000)">
                        <i class="fas fa-plus"></i> 
                    </button>
                    </p>
                </div>
                <div class="order-item foods">
                    <img alt="A bowl of Mie Rebus, a popular Indonesian noodle soup" height="80" src="https://storage.googleapis.com/a1aa/image/n25ECTktyqrEG54MJEEc6jWdphALEj3fuXe6xt4ZQaAOQt3TA.jpg" width="150"/>
                    <h3>Mie Rebus</h3>
                    <p>Rp 25.000
                    <button class="add-btn" onclick="addToCart('Mie Rebus', 25000)">
                        <i class="fas fa-plus"></i> 
                    </button>
                    </p>
                </div>
                <div class="order-item foods">
                    <img alt="A plate of Mie Nyemek, a semi-wet Indonesian noodle dish" height="80" src="https://storage.googleapis.com/a1aa/image/AclIi2q8smZDNtldBRADT0NL6Ut69BCT7v3xGfYwX2ODo27JA.jpg" width="150"/>
                    <h3>Mie Nyemek</h3>
                    <p>Rp 25.000
                    <button class="add-btn" onclick="addToCart('Mie Nyemek', 25000)">
                        <i class="fas fa-plus"></i> 
                    </button>
                    </p>
                </div>
                <div class="order-item snacks">
                    <img alt="A plate of Pisang Bakar, grilled banana with toppings" height="80" src="https://storage.googleapis.com/a1aa/image/J36pBhQ4WzqcIN0s50sS4WQXVW5vOXMvJPKbh9NnDgFCU79E.jpg" width="150"/>
                    <h3>Pisang Bakar</h3>
                    <p>Rp 20.000
                    <button class="add-btn" onclick="addToCart('Pisang Bakar', 20000)">
                        <i class="fas fa-plus"></i> 
                    </button>
                    </p>
                </div>
                <div class="order-item snacks">
                    <img alt="A plate of Roti Bakar, grilled bread with various toppings" height="80" src="https://storage.googleapis.com/a1aa/image/Srm8mh6j7yqsLp6dOJIJV77hdIeqePDCmmOL30ASt3JHQt3TA.jpg" width="150"/>
                    <h3>Roti Bakar</h3>
                    <p>Rp 20.000
                    <button class="add-btn" onclick="addToCart('Roti Bakar', 20000)">
                        <i class="fas fa-plus"></i> 
                    </button>
                    </p>
                </div>
                <div class="order-item drinks">
                    <img alt="A glass of Es Teh Manis, sweet iced tea" height="80" src="https://storage.googleapis.com/a1aa/image/kaGIj7yVWHrHAxw0Ni9FQF08eJIfPU9tsVAuf923tjAYgavnA.jpg" width="150"/>
                    <h3>Es Teh Manis</h3>
                    <p>Rp 5.000
                    <button class="add-btn" onclick="addToCart('Es Teh Manis', 5000)">
                        <i class="fas fa-plus"></i> 
                    </button>
                    </p>
                </div>
            </div>
        </div>
        <script>
            function filterMenu(category) {
                var items = document.getElementsByClassName('order-item');
                for (var i = 0; i < items.length; i++) {
                    if (category === 'all') {
                        items[i].style.display = 'block';
                    } else {
                        if (items[i].classList.contains(category)) {
                            items[i].style.display = 'block';
                        } else {
                            items[i].style.display = 'none';
                        }
                    }
                }
                // Remove active class from all buttons
                var buttons = document.querySelectorAll('.menu-category button');
                buttons.forEach(function(button) {
                    button.classList.remove('active');
                });
                // Add active class to the clicked button
                document.getElementById(category + '-btn').classList.add('active');
            }

            function searchMenu(query) {
                var items = document.getElementsByClassName('order-item');
                for (var i = 0; i < items.length; i++) {
                    var itemName = items[i].getElementsByTagName('h3')[0].innerText.toLowerCase();
                    if (itemName.includes(query.toLowerCase())) {
                        items[i].style.display = 'block';
                    } else {
                        items[i].style.display = 'none';
                    }
                }
            }
    </script>
       <div class="order-summary">
       <h2>Orders #00001</h2>
       <br>
            <div class="order-list">
            </div>
            <div class="order-total">
        <ul id="cart-items" style=""><li style="">Mie Goreng - Rp25000 <button class="cancel-btn" onclick="removeFromCart('Mie Goreng')">-</button></li>
        <li style="">Mie Rebus - Rp25000 <button class="cancel-btn" onclick="removeFromCart('Mie Rebus')">-</button></li></ul>
        <p id="total-items" style="">Total Barang: </p>
        <p id="total-price">Total Harga: Rp50000</p>
        <!-- Form untuk mengirimkan data ke PHP -->
        <form id="cart-form" method="POST" action="confirmation_payment.php">
        <input type="hidden" id="cart-data" name="cart-data" />
        <button type="submit" class="payment-btn" onclick="submitCart()">Continue to Payment</button>
        </form>

        </div>
        <script>
let cart = [];
function submitCart() {
    const cartInput = document.getElementById('cart-data');
    // Verifikasi data cart
    console.log("Sending cart data: ", JSON.stringify(cart)); // Debugging
    cartInput.value = JSON.stringify(cart); // Kirim data cart dalam format JSON
}


// Fungsi untuk menambahkan item ke keranjang
function addToCart(productName, productPrice) {
    // Cari item yang sama di keranjang
    const existingProduct = cart.find(item => item.name === productName);

    if (existingProduct) {
        // Jika produk sudah ada, tambahkan jumlahnya
        existingProduct.quantity += 1;
    } else {
        // Jika produk belum ada, tambahkan ke keranjang dengan quantity = 1
        const product = {
            name: productName,
            price: productPrice,
            quantity: 1
        };
        cart.push(product);
    }
    updateCartDisplay();
}
// Fungsi untuk menghapus item dari keranjang
function removeFromCart(productName) {
    const productIndex = cart.findIndex(item => item.name === productName);
    if (productIndex !== -1) {
        const product = cart[productIndex];
        if (product.quantity > 1) {
            // Jika jumlah lebih dari 1, kurangi quantity
            product.quantity -= 1;
        } else {
            // Jika jumlah 1, hapus item dari keranjang
            cart.splice(productIndex, 1);
        }
    }
    updateCartDisplay();
}
// Fungsi untuk memperbarui tampilan keranjang
function updateCartDisplay() {
    const cartItemsList = document.getElementById('cart-items');
    const totalItemsDisplay = document.getElementById('total-items');
    const totalPriceDisplay = document.getElementById('total-price');
    // Membersihkan tampilan item di keranjang
    cartItemsList.innerHTML = '';
    // Menghitung total barang dan total harga
    let totalItems = 0;
    let totalPrice = 0;
    cart.forEach(item => {
        const listItem = document.createElement('li');
        listItem.innerHTML = `${item.name} x ${item.quantity} - Rp${item.price * item.quantity} 
            <button class="cancel-btn" onclick="removeFromCart('${item.name}')">-</button>`;
        cartItemsList.appendChild(listItem);

        totalItems += item.quantity;
        totalPrice += item.price * item.quantity;
    });
    // Memperbarui tampilan total barang dan total harga
    totalItemsDisplay.innerHTML = `Total Barang: ${totalItems}`;
    totalPriceDisplay.innerHTML = `Total Harga: Rp${totalPrice}`;
}
// Memastikan keranjang kosong saat halaman dimuat
updateCartDisplay();
    </script>
</body>
</html>