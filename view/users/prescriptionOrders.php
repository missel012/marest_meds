<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
?>

<div class="pagetitle">
    <h1>Prescription Orders</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active"><a href="prescriptionOrders.php">Prescription Orders</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row align-items-top">
        <div class="d-flex flex-row w-100">
            <!-- Left Card -->
            <div class="card" style="flex: 3; margin-right: 10px;">
                <div class="card-body" style="padding: 1rem;">
                    <!-- Pills Tabs -->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">All Items</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Generic Medicines</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Immunity Boosters</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2" id="myTabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row">
                                <!-- All Items Tab -->
                                <?php for ($i = 0; $i < 16; $i++): ?>
                                <div class="col-lg-3">
                                    <button type="button" class="btn custom-card mt-2 mb-2" onclick="addToCart('<?php echo $i < 8 ? 'Loperamide' : 'Ascorbic'; ?>', 60, '<?php echo $i < 8 ? 'loperamide.jpg' : 'ascorbic.jpg'; ?>')">
                                        <img src="../../assets/img/<?php echo $i < 8 ? 'loperamide.jpg' : 'ascorbic.jpg'; ?>" class="card-img-top custom-card-img" alt="...">
                                        <div class="card-body text-center" style="font-size: 0.875rem;">
                                            <h5 class="card-title" style="font-size: 0.75rem; margin-top: -1rem"><?php echo $i < 8 ? 'Loperamide 2 mg Capsule - 10’S' : 'Ascorbic Acid 500 mg Tablet - 10’S'; ?></h5>
                                            <h6 class="card-subtitle mb-2 text-muted" style="font-size: 0.75rem;">Quantity in Stock: <span id="stock-<?php echo $i; ?>">100</span></h6>
                                            <p class="card-text" style="font-size: 0.75rem;">₱60.00</p>
                                        </div>
                                    </button><!-- End Card with an image on top -->
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="row">
                                <!-- Generic Medicines Tab -->
                                <?php for ($i = 0; $i < 8; $i++): ?>
                                <div class="col-lg-3">
                                    <button type="button" class="btn custom-card mt-2 mb-2" onclick="addToCart('Loperamide', 60, 'loperamide.jpg')">
                                        <img src="../../assets/img/loperamide.jpg" class="card-img-top custom-card-img" alt="...">
                                        <div class="card-body text-center" style="font-size: 0.875rem;">
                                            <h5 class="card-title" style="font-size: 0.75rem; margin-top: -1rem">Loperamide 2 mg Capsule - 10’S</h5>
                                            <h6 class="card-subtitle mb-2 text-muted" style="font-size: 0.75rem;">Quantity in Stock: <span id="stock-generic-<?php echo $i; ?>">100</span></h6>
                                            <p class="card-text" style="font-size: 0.75rem;">₱60.00</p>
                                        </div>
                                    </button><!-- End Card with an image on top -->
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="row">
                                <!-- Immunity Boosters Tab -->
                                <?php for ($i = 0; $i < 8; $i++): ?>
                                <div class="col-lg-3">
                                    <button type="button" class="btn custom-card mt-2 mb-2" onclick="addToCart('Ascorbic', 60, 'ascorbic.jpg')">
                                        <img src="../../assets/img/ascorbic.jpg" class="card-img-top custom-card-img" alt="...">
                                        <div class="card-body text-center" style="font-size: 0.875rem;">
                                            <h5 class="card-title" style="font-size: 0.75rem; margin-top: -1rem">Ascorbic Acid 500 mg Tablet - 10’S</h5>
                                            <h6 class="card-subtitle mb-2 text-muted" style="font-size: 0.75rem;">Quantity in Stock: <span id="stock-immunity-<?php echo $i; ?>">100</span></h6>
                                            <p class="card-text" style="font-size: 0.75rem;">₱60.00</p>
                                        </div>
                                    </button><!-- End Card with an image on top -->
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div><!-- End Pills Tabs -->
                </div>
            </div><!-- End Left Card -->

            <!-- Right Card -->
            <div class="card" style="flex: 1.5; margin-left: 10px;">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <div id="cart-items">
                        <!-- Cart items will be dynamically added here -->
                    </div>
                    <div id="cart-summary" style="display: none;">
                        <div class="card mt-3" style="background-color: #E9ECE9; box-shadow: none;">
                            <div class="card-body">
                                <h5 class="card-title" style="color: black;">Total</h5>
                                <p id="total-amount" class="card-text" style="color: black;">₱0.00</p>
                                <button type="button" class="btn btn-outline-primary" id="pay-now-btn">Pay Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Right Card -->
        </div>
    </div>
</section>

<?php
include("./includes/footer.php");
?>

<style>
    /* Custom card styles */
    .custom-card {
        border: 1px solid #DB5C79;
        background-color: white;
        transition: all 0.3s ease;
        text-align: left;
        padding: 0;
    }

    .custom-card:hover {
        border-color: #FFDEE6;
        background-color: #FFDEE6;
    }

    /* Custom button styles */
    .btn-custom {
        border: 1px solid #DB5C79;
        background-color: white;
        color: #DB5C79;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        border-color: #FFDEE6;
        background-color: #FFDEE6;
        color: #DB5C79;
    }

    /* Custom image styles */
    .custom-card-img {
        transition: all 0.3s ease;
        width: 100%;
    }

    .custom-card:hover .custom-card-img {
        opacity: 0.5;
    }

    .custom-card .card-body {
        padding: 1rem;
    }

    /* Custom active tab styles */
    .nav-pills .nav-link.active {
        background-color: #6CCF54;
    }

    /* Custom inactive tab styles */
    .nav-pills .nav-link {
        color: black;
    }

    /* Cart item styles */
    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .cart-item img {
        width: 100px; /* Same size as left card */
        height: auto; /* Maintain aspect ratio */
        margin-right: 10px;
    }

    .cart-item .item-details {
        flex: 1;
        font-size: 0.875rem; /* Same font size as left card */
        display: flex;
        flex-direction: column;
        justify-content: center; /* Center vertically */
    }

    .cart-item .item-quantity {
        display: flex;
        align-items: center;
    }

    .cart-item .item-quantity button {
        background-color: #6CCF54;
        border: none;
        color: white;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 0.25rem; /* Same border radius as Pay Now button */
        width: 40px; /* Same width as Pay Now button */
        height: 40px; /* Same height as Pay Now button */
    }

    .cart-item .item-quantity button:hover {
        background-color: #EDFFE9;
        color: #6CCF54;
    }

    .cart-item .item-quantity span {
        margin: 0 10px;
    }

    .cart-item .item-quantity .icon {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin-left: 10px; /* Add space between the plus button and the delete button */
    }

    .cart-item .item-quantity .icon i {
        font-size: 1.2rem;
        color: #DB5C79; /* Change trash icon color */
        transition: color 0.3s ease;
    }

    .cart-item .item-quantity .icon:hover i {
        color: #FFDEE6; /* Change trash icon color on hover */
    }

    /* Pay Now button styles */
    #pay-now-btn {
        background-color: #6CCF54;
        border-color: #6CCF54;
        color: white;
        border-radius: 0.25rem; /* Ensure border radius matches */
        width: 100px; /* Set width */
        height: 40px; /* Set height */
    }

    #pay-now-btn:hover {
        background-color: #EDFFE9;
        border-color: #EDFFE9;
        color: #6CCF54;
    }
</style>

<script>
    let cart = {};

    function addToCart(itemName, itemPrice, itemImage) {
        if (!cart[itemName]) {
            cart[itemName] = { quantity: 0, price: itemPrice, image: itemImage };
        }
        cart[itemName].quantity++;
        updateCart();
    }

    function updateCart() {
        const cartItems = document.getElementById('cart-items');
        const cartSummary = document.getElementById('cart-summary');
        const totalAmount = document.getElementById('total-amount');
        let total = 0;

        cartItems.innerHTML = '';
        for (const itemName in cart) {
            const item = cart[itemName];
            const totalPrice = item.quantity * item.price;
            total += totalPrice;
            cartItems.innerHTML += `
                <div class="cart-item">
                    <img src="../../assets/img/${item.image}" alt="${itemName}">
                    <div class="item-details">
                        <h5 class="card-title" style="font-size: 0.75rem; margin-top: -1rem">${itemName}</h5>
                        <p class="card-text" style="font-size: 0.75rem;">₱${totalPrice.toFixed(2)}</p>
                    </div>
                    <div class="item-quantity">
                        <button onclick="changeQuantity('${itemName}', -1)">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="changeQuantity('${itemName}', 1)">+</button>
                        <div class="icon" onclick="removeFromCart('${itemName}')">
                            <i class="bi bi-trash"></i>
                        </div>
                    </div>
                </div>
            `;
        }

        totalAmount.innerText = `₱${total.toFixed(2)}`;
        cartSummary.style.display = total > 0 ? 'block' : 'none';
    }

    function changeQuantity(itemName, change) {
        if (cart[itemName]) {
            cart[itemName].quantity += change;
            if (cart[itemName].quantity <= 0) {
                delete cart[itemName];
            }
            updateCart();
        }
    }

    function removeFromCart(itemName) {
        if (cart[itemName]) {
            delete cart[itemName];
            updateCart();
        }
    }
</script>