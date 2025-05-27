<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php");

// Fetch inventory items grouped by category
$query = "SELECT * FROM inventory ORDER BY `group` ASC, inventoryId ASC";
$result = mysqli_query($conn, $query);

$inventory = [];
while ($row = mysqli_fetch_assoc($result)) {
    $inventory[$row['group']][] = $row;
}

// Fetch the last order ID from the database
$orderQuery = "SELECT MAX(orderId) as lastOrderId FROM `order`";
$orderResult = mysqli_query($conn, $orderQuery);
$orderRow = mysqli_fetch_assoc($orderResult);
$nextOrderId = $orderRow['lastOrderId'] + 1;
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
                    <div class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="overflow-x: auto; white-space: nowrap;">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">All Items</button>
                        <?php foreach ($inventory as $category => $items) : ?>
                            <button class="nav-link" id="pills-<?php echo strtolower(str_replace(' ', '-', $category)); ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?php echo strtolower(str_replace(' ', '-', $category)); ?>" type="button" role="tab" aria-controls="pills-<?php echo strtolower(str_replace(' ', '-', $category)); ?>" aria-selected="false"><?php echo htmlspecialchars($category); ?></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="tab-content pt-2" id="myTabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row">
                                <!-- All Items Tab -->
                                <?php foreach ($inventory as $category => $items) : ?>
                                    <?php foreach ($items as $item) : ?>
                                        <div class="col-lg-3">
                                            <button type="button" class="btn custom-card mt-2 mb-2"
                                                onclick="addToCart('<?php echo $item['genericName']; ?>', '<?php echo $item['brandName']; ?>', <?php echo $item['price']; ?>, '<?php echo $item['group']; ?>', <?php echo $item['inventoryId']; ?>)">
                                                <div class="card-body text-center" style="font-size: 0.875rem;">
                                                    <h5 class="card-title" style="font-size: 0.75rem; margin-top: -1rem"><?php echo $item['genericName'] . ' ' . $item['brandName'] . ' ' . $item['milligram'] . ' mg ' . $item['dosageForm']; ?></h5>
                                                    <h6 class="card-subtitle mb-2 text-muted" style="font-size: 0.75rem;">Quantity in Stock: <span id="stock-<?php echo $item['inventoryId']; ?>"><?php echo $item['quantity']; ?></span></h6>
                                                    <p class="card-text" style="font-size: 0.75rem;">₱<?php echo number_format($item['price'], 2); ?></p>
                                                </div>
                                            </button><!-- End Card with an image on top -->
                                        </div>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php foreach ($inventory as $category => $items) : ?>
                            <div class="tab-pane fade" id="pills-<?php echo strtolower(str_replace(' ', '-', $category)); ?>" role="tabpanel" aria-labelledby="pills-<?php echo strtolower(str_replace(' ', '-', $category)); ?>-tab">
                                <div class="row">
                                    <!-- Category Tab -->
                                    <?php foreach ($items as $item) : ?>
                                        <div class="col-lg-3">
                                            <button type="button" class="btn custom-card mt-2 mb-2"
                                                onclick="addToCart('<?php echo $item['genericName']; ?>', '<?php echo $item['brandName']; ?>', <?php echo $item['price']; ?>, '<?php echo $item['group']; ?>', <?php echo $item['inventoryId']; ?>)">
                                                <div class="card-body text-center" style="font-size: 0.875rem;">
                                                    <h5 class="card-title" style="font-size: 0.75rem; margin-top: -1rem"><?php echo $item['genericName'] . ' ' . $item['brandName'] . ' ' . $item['milligram'] . ' mg ' . $item['dosageForm']; ?></h5>
                                                    <h6 class="card-subtitle mb-2 text-muted" style="font-size: 0.75rem;">Quantity in Stock: <span id="stock-<?php echo $item['inventoryId']; ?>"><?php echo $item['quantity']; ?></span></h6>
                                                    <p class="card-text" style="font-size: 0.75rem;">₱<?php echo number_format($item['price'], 2); ?></p>
                                                </div>
                                            </button><!-- End Card with an image on top -->
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div><!-- End Pills Tabs -->
                </div>
            </div><!-- End Left Card -->

            <!-- Right Card -->
            <div class="card" style="flex: 1.5; margin-left: 10px;">
                <div class="card-body" style="font-family: inherit;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Order Summary</h5>
                        <button type="button" class="btn btn-outline-danger" id="delete-btn" style="display: none;" onclick="clearCart()">Delete All</button>
                    </div>
                    <div id="cart-items">
                        <!-- Cart items will be dynamically added here -->
                    </div>
                    <div id="cart-summary" style="display: none; font-family: Consolas;">
                        <div class="card mt-3" style="background-color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08), 0 8px 10px rgba(0, 0, 0, 0.15);">
                            <div class="card-body">
                                <h5 class="card-title" style="color: black; ">Total</h5>
                                <p id="total-amount" class="card-text" style="color: black; ">₱0.00</p>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-primary" id="print-receipt-btn">Print Receipt</button>
                                </div>
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
        width: 100%;
        height: 130px;
        /* Set a fixed height */
        display: flex;
        flex-direction: column;
        justify-content: center;
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
        color: #C53D3D;
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

    .cart-item .item-details {
        flex: 1;
        font-size: 0.875rem;
        /* Same font size as left card */
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* Center vertically */
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
        border-radius: 0.25rem;
        /* Same border radius as Pay Now button */
        width: 40px;
        /* Same width as Pay Now button */
        height: 40px;
        /* Same height as Pay Now button */
    }

    .cart-item .item-quantity button:hover {
        background-color: #5bbd4a;
        color: white;
        /* Ensure text color remains white */
    }

    .cart-item .item-quantity span {
        margin: 0 10px;
    }

    .cart-item .item-quantity .icon {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin-left: 10px;
        /* Add space between the plus button and the delete button */
    }

    .cart-item .item-quantity .icon i {
        font-size: 1.2rem;
        color: #F25353;
        /* Change trash icon color */
        transition: color 0.3s ease;
    }

    .cart-item .item-quantity .icon:hover i {
        color: #D74769;
        /* Change trash icon color on hover */
    }

    /* Pay Now button styles */
    #print-receipt-btn {
        background-color: #6CCF54;
        border-color: #6CCF54;
        color: white;
        border-radius: 0.25rem;
        /* Ensure border radius matches */
        width: 140px;
        /* Set width */
        height: 40px;
        /* Set height */
    }

    #print-receipt-btn:hover {
        background-color: #5bbd4a;
        border-color: #5bbd4a;
        color: white;
        /* Ensure text color remains white */
    }

    /* Scrollable tabs */
    #pills-tab {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    #pills-tab::-webkit-scrollbar {
        display: none;
        /* Hide scrollbar */
    }

    .nav-pills .nav-link {
        display: inline-block;
        white-space: nowrap;
        /* Ensure tabs are in one row */
    }

    .nav-pills {
        flex-wrap: nowrap;
        /* Prevent tabs from wrapping to the next line */
    }

    #delete-btn {
        background-color: #F25353;
        border-color: #F25353;
        color: white;
        border-radius: 0.25rem;
        width: 100px;
        height: 40px;
        margin-left: auto;
        /* Push it to the right */
        display: block;
        /* Ensure it stays aligned */
    }

    #delete-btn:hover {
        background-color: #C53D3D;
        border-color: #C53D3D;
        color: white;
    }

    /* Center logo horizontally */
    .logo_bw {
        line-height: 1;
    }

    @media (min-width: 1200px) {
        .logo_bw {
            width: 280px;
        }
    }

    .logo_bw img {
        max-height: 26px;
        margin-right: 6px;
    }

    .logo_bw span {
        font-size: 26px;
        font-weight: 700;
        color: #012970;
        font-family: "Nunito", sans-serif;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let cart = {};
    let nextOrderId = <?php echo $nextOrderId; ?>;

    async function fetchLatestOrderId() {
        try {
            const response = await fetch('fetch_latest_order_id.php');
            const data = await response.json();
            if (data.success) {
                nextOrderId = data.nextOrderId;
            } else {
                console.error('Error fetching latest order ID:', data.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function addToCart(genericName, brandName, itemPrice, group, inventoryId) {
        await fetchLatestOrderId(); // Fetch updated Order ID before adding to cart

        const itemName = `${genericName} ${brandName}`;
        const stockElements = document.querySelectorAll(`#stock-${inventoryId}`);
        const originalQuantity = parseInt(stockElements[0].innerText);

        if (!cart[itemName]) {
            cart[itemName] = {
                quantity: 0,
                price: itemPrice,
                inventoryId: inventoryId,
                medicineGroup: group,
                originalQuantity: originalQuantity
            };
        }
        if (cart[itemName].quantity < originalQuantity) {
            cart[itemName].quantity++;
            updateStock(inventoryId, -1);
            updateCart();
        }
    }


    function updateCart() {
        const cartItems = document.getElementById('cart-items');
        const cartSummary = document.getElementById('cart-summary');
        const totalAmount = document.getElementById('total-amount');
        const deleteBtn = document.getElementById('delete-btn'); // Reference delete button
        let total = 0;

        cartItems.innerHTML = '';

        for (const itemName in cart) {
            const item = cart[itemName];
            const totalPrice = item.quantity * item.price;
            total += totalPrice;
            cartItems.innerHTML += `
            <div class="cart-item">
                <div class="item-details">
                    <h5 class="card-title" style="font-size: 0.75rem; margin-top: -1rem">${itemName}</h5>
                    <p class="card-text" style="font-size: 0.75rem;">₱${totalPrice.toFixed(2)}</p>
                </div>
                <div class="item-quantity">
                    <button onclick="changeQuantity('${itemName}', -1)" ${item.quantity === 0 ? 'disabled' : ''}>-</button>
                    <span>${item.quantity}</span>
                    <button onclick="changeQuantity('${itemName}', 1)" ${item.quantity === item.originalQuantity ? 'disabled' : ''}>+</button>
                    <div class="icon" onclick="removeFromCart('${itemName}')">
                        <i class="bi bi-trash"></i>
                    </div>
                </div>
            </div>
        `;
        }

        // Add the "Print Receipt" button only if there are items in the cart
        if (Object.keys(cart).length > 0) {
            cartItems.innerHTML += `
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-primary" id="print-receipt-btn" onclick="showReceipt()">Print Receipt</button>
            </div>
            `;
        }

        totalAmount.innerText = `₱${total.toFixed(2)}`;
        cartSummary.style.display = 'none'; // Hide the cart summary initially

        // Show delete button if cart has items, hide otherwise
        if (Object.keys(cart).length > 0) {
            deleteBtn.style.display = "block";
        } else {
            deleteBtn.style.display = "none";
        }
    }

    async function showReceipt() {
        await fetchLatestOrderId(); // Ensure latest order ID is fetched

        const cartSummary = document.getElementById('cart-summary');
        const cartItems = document.getElementById('cart-items');
        let total = 0;

        // Capture the time when Print Receipt is clicked
        const now = new Date();
        const formattedDateTime = now.toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        });

        let receiptContent = `
        <div class="d-flex align-items-center justify-content-center">
            <a class="logo_bw d-flex align-items-center" style="margin-top: 30px; justify-content: center;">
                <img src="../../assets/img/marest_logo_bw.png" alt="Logo">
                <span class="d-none d-lg-block">
                    <span style="color:  black ; font-weight: bold;">Marest</span>
                    <span style="color: #525252; font-weight: bold;">Meds</span>
                </span>
            </a>
        </div>
        <div id="receipt-datetime" class="text-center" style="margin-top: 10px; font-size: 0.875rem;">${formattedDateTime}</div>
        <div class="text-center" style="margin-top: 10px; font-size: 0.875rem;">
            <hr style="border-top: 1px dashed black;">
            Order ID: ${nextOrderId}
            <hr style="border-top: 1px dashed black;">
        </div>
    `;

        for (const itemName in cart) {
            const item = cart[itemName];
            const totalPrice = item.quantity * item.price;
            total += totalPrice;

            receiptContent += `
        <div style="display: flex; justify-content: space-between; font-family: inherit; color: black; font-size: 0.875rem; margin-top: -10px;">
            <h5 class="card-title" style="font-size: 0.75rem; font-family: Consolas; margin-top: -1rem; flex: 1; color: black;">${itemName}</h5>
            <span style="font-size: 0.75rem; flex: 1; text-align: center;">${item.quantity}</span>
            <p class="card-text" style="font-size: 0.75rem; flex: 1; text-align: right;">₱${totalPrice.toFixed(2)}</p>
        </div>
    `;
        }

        receiptContent += `
        <hr style="border-top: 1px solid black; margin-top: -5px; margin-bottom: -5px;">
        <div style="display: flex; justify-content: space-between; font-family: inherit; color: black; font-size: 0.875rem; align-items: center;">
            <h5 class="card-title" style="color: black; font-family: Consolas; font-size: 0.875rem; margin-right: auto;">Total</h5>
            <p id="total-amount" class="card-text" style="color: black; font-size: 0.875rem; text-align: right; margin-left: auto;">₱${total.toFixed(2)}</p>
        </div>
        <div style="display: flex; justify-content: center; font-family: inherit; color: black; font-size: 0.875rem; align-items: center; margin-bottom: 10px;">
            <span style="font-size: 0.875rem; color: black; font-family: Consolas; ">Thank you for shopping with us!</span>
        </div>
    `;

        // Clear the cart items and show the receipt
        cartItems.innerHTML = '';
        cartSummary.style.display = 'block';
        cartSummary.querySelector('.card-body').innerHTML = receiptContent;

        // Send the cart data to the server
        fetch('post_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(cart)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Your order has been placed successfully!',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'There was an error placing your order. Please try again.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'There was an error placing your order. Please try again.',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                console.error('Error:', error);
            });

        // Clear the cart
        cart = {};
    }

    function updateReceiptOrderId() {
        const receiptOrderIdElement = document.querySelector('.text-center').querySelector('div:nth-child(3)');
        if (receiptOrderIdElement) {
            receiptOrderIdElement.innerHTML = `
                <hr style="border-top: 1px dashed black;">
                Order ID: ${nextOrderId}
                <hr style="border-top: 1px dashed black;">
            `;
        }
    }

    function updateDateTime() {
        const dateTimeElement = document.getElementById('receipt-datetime');
        if (dateTimeElement) {
            const now = new Date();
            const formattedDateTime = now.toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true
            });
            dateTimeElement.innerText = formattedDateTime;
        }
    }

    // Function to clear the cart
    function clearCart() {
        for (const itemName in cart) {
            updateStock(cart[itemName].inventoryId, cart[itemName].quantity);
        }
        cart = {}; // Clear the cart object
        updateCart();
    }


    function changeQuantity(itemName, change) {
        if (cart[itemName]) {
            const newQuantity = cart[itemName].quantity + change;
            if (newQuantity >= 0 && newQuantity <= cart[itemName].originalQuantity) {
                cart[itemName].quantity = newQuantity;
                updateStock(cart[itemName].inventoryId, -change); // Reverse the change for stock
                if (cart[itemName].quantity === 0) {
                    delete cart[itemName];
                }
                updateCart();
            }
        }
    }

    function removeFromCart(itemName) {
        if (cart[itemName]) {
            updateStock(cart[itemName].inventoryId, cart[itemName].quantity);
            delete cart[itemName];
            updateCart();
        }
    }

    function updateStock(inventoryId, change) {
        const stockElements = document.querySelectorAll(`#stock-${inventoryId}`);
        stockElements.forEach(stockElement => {
            const currentStock = parseInt(stockElement.innerText);
            stockElement.innerText = currentStock + change;
        });
    }

    document.getElementById('delete-all-btn').addEventListener('click', function() {
        for (const itemName in cart) {
            updateStock(cart[itemName].inventoryId, cart[itemName].quantity);
        }
        cart = {};
        updateCart();
    });
</script>