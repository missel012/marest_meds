<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php"); // Ensure this file contains your database connection

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
    <div class="d-flex justify-content-between align-items-center">
        <h1>Products</h1>
    </div>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active"><a href="products.php">Products</a></li>
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
                    <!-- filepath: c:\xampp\htdocs\datahan_eblacas\view\users\products.php -->
                    <div class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="overflow-x: auto; white-space: nowrap;">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">All Items</button>
                        <?php
                        // Normalize and deduplicate categories
                        $uniqueCategories = [];
                        foreach ($inventory as $category => $items) {
                            $normalizedCategory = ucwords(strtolower($category)); // Normalize capitalization
                            if (!in_array($normalizedCategory, $uniqueCategories)) {
                                $uniqueCategories[] = $normalizedCategory;
                        ?>
                                <button class="nav-link" id="pills-<?php echo strtolower(str_replace(' ', '-', $normalizedCategory)); ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?php echo strtolower(str_replace(' ', '-', $normalizedCategory)); ?>" type="button" role="tab" aria-controls="pills-<?php echo strtolower(str_replace(' ', '-', $normalizedCategory)); ?>" aria-selected="false">
                                    <?php echo htmlspecialchars($normalizedCategory); ?>
                                </button>
                        <?php
                            }
                        }
                        ?>
                    </div>

                    <div class="tab-content pt-2" id="myTabContent">
                        <!-- All Items Tab -->
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row">
                                <?php foreach ($inventory as $category => $items) : ?>
                                    <?php foreach ($items as $item) : ?>
                                        <div class="col-lg-4">
                                            <button type="button" class="btn custom-card mt-2 mb-2"
                                                onclick="showDrugDetails(
                                                    '<?php echo base64_encode($item['image']); ?>',
                                                    '<?php echo htmlspecialchars($item['genericName']); ?>',
                                                    '<?php echo htmlspecialchars($item['brandName']); ?>',
                                                    '<?php echo $item['milligram']; ?>',
                                                    '<?php echo htmlspecialchars($item['dosageForm']); ?>',
                                                    '<?php echo htmlspecialchars($item['group']); ?>',
                                                    '<?php echo $item['quantity']; ?>',
                                                    '<?php echo number_format($item['price'], 2); ?>',
                                                    '<?php echo $item['inventoryId']; ?>'
                                                )">
                                                <div class="card-body text-center" style="font-size: 0.875rem;">
                                                    <!-- Image on top -->
                                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>"
                                                        alt="Product Image"
                                                        class="img-fluid mb-2 custom-card-img-square" />

                                                    <h5 class="card-title" style="font-size: 0.75rem; margin-top: -0.5rem">
                                                        <?php echo $item['genericName'] . ' ' . $item['brandName'] . ' ' . $item['milligram'] . ' mg ' . $item['dosageForm']; ?>
                                                    </h5>
                                                    <h6 class="card-subtitle mb-2 text-muted" style="font-size: 0.75rem;">
                                                        Quantity in Stock:
                                                        <span id="stock-<?php echo $item['inventoryId']; ?>">
                                                            <?php echo $item['quantity']; ?>
                                                        </span>
                                                    </h6>
                                                    <p class="card-text" style="font-size: 0.75rem;">₱<?php echo number_format($item['price'], 2); ?></p>
                                                </div>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Category Tabs -->
                        <?php foreach ($inventory as $category => $items) : ?>
                            <div class="tab-pane fade" id="pills-<?php echo strtolower(str_replace(' ', '-', $category)); ?>" role="tabpanel" aria-labelledby="pills-<?php echo strtolower(str_replace(' ', '-', $category)); ?>-tab">
                                <div class="row">
                                    <?php foreach ($items as $item) : ?>
                                        <div class="col-lg-4">
                                            <button type="button" class="btn custom-card mt-2 mb-2"
                                                onclick="showDrugDetails(
                                                    '<?php echo base64_encode($item['image']); ?>',
                                                    '<?php echo htmlspecialchars($item['genericName']); ?>',
                                                    '<?php echo htmlspecialchars($item['brandName']); ?>',
                                                    '<?php echo $item['milligram']; ?>',
                                                    '<?php echo htmlspecialchars($item['dosageForm']); ?>',
                                                    '<?php echo htmlspecialchars($item['group']); ?>',
                                                    '<?php echo $item['quantity']; ?>',
                                                    '<?php echo number_format($item['price'], 2); ?>',
                                                    '<?php echo $item['inventoryId']; ?>' 
                                                )">
                                                <div class="card-body text-center" style="font-size: 0.875rem;">
                                                    <!-- Image on top -->
                                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>"
                                                        alt="Product Image"
                                                        class="img-fluid mb-2 custom-card-img-square" />

                                                    <h5 class="card-title" style="font-size: 0.75rem; margin-top: -0.5rem">
                                                        <?php echo $item['genericName'] . ' ' . $item['brandName'] . ' ' . $item['milligram'] . ' mg ' . $item['dosageForm']; ?>
                                                    </h5>
                                                    <h6 class="card-subtitle mb-2 text-muted" style="font-size: 0.75rem;">
                                                        Quantity in Stock:
                                                        <span id="stock-<?php echo $item['inventoryId']; ?>">
                                                            <?php echo $item['quantity']; ?>
                                                        </span>
                                                    </h6>
                                                    <p class="card-text" style="font-size: 0.75rem;">₱<?php echo number_format($item['price'], 2); ?></p>
                                                </div>
                                            </button>
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
                        <h5 class="card-title text-black">
                            <i class="bi bi-cart-fill" style="margin-right: 6px;"></i> Cart
                        </h5>
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

<!-- Drug Details Modal -->
<div class="modal fade" id="drugDetailModal" tabindex="-1" aria-labelledby="drugDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"><!-- Changed modal-lg to modal-xl -->
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #6CCF54;">
                <h5 class="modal-title text-white" id="drugDetailModalLabel">Drug Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>

            <div class="modal-body d-flex justify-content-center align-items-center ml-10">
                <!-- Increased margin-left to 5rem -->
                <div class="row align-items-center" style="margin-left: 5rem;">
                    <!-- Image on the left -->
                    <div class="col-md-4 text-center mb-3 mb-md-0">
                        <img id="modalDrugImage" src="" class="img-fluid rounded" style="width: 100%; max-width: 300px; height: auto; object-fit: cover;" alt="Drug Image" />
                    </div>

                    <!-- Details on the right -->
                    <div class="col-md-8 d-flex flex-column justify-content-center">
                        <h5 id="modalDrugName" class="mb-3"></h5>
                        <p id="modalDosage" class="mb-1"></p>
                        <p id="modalGroup" class="mb-1"><strong>Category:</strong> <span></span></p>
                        <p id="modalStock" class="mb-1"><strong>Stock:</strong> <span></span></p>
                        <p id="modalPrice" class="fw-bold"><strong>Price:</strong> ₱<span></span></p>

                        <!-- Add to Cart button with custom color -->
                        <button id="modalAddToCartBtn" class="btn mt-3 w-50 align-self-start"
                            style="background-color: #6CCF54; color: white;">
                            Add to Cart
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header text-white" style="background-color: #6CCF54;">
                <h5 class="modal-title text-white" id="checkoutModalLabel">Order Summary</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Ordered Products -->
                <h5 class="card-title text-black mb-3">Ordered Products</h5>
                <div id="order-summary" class="mb-4"></div>

                <!-- Address -->
                <div class="mb-3">
                    <label for="address" class="form-label"><strong>Address:</strong></label>
                    <input type="text" id="address" class="form-control" placeholder="Enter your address" required>
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label"><strong>Phone Number:</strong></label>
                    <input type="text" id="phoneNumber" class="form-control" placeholder="Enter your phone number" required>
                </div>

                <!-- Dates -->
                <div class="mt-3">
                    <p><strong>Order Date:</strong> <span id="orderDate"></span></p>
                    <p><strong>Estimated Delivery:</strong> <span id="estimatedDelivery"></span></p>
                </div>

                <!-- Cancel Section (Initially Hidden) -->
                <div id="cancel-section" class="mt-3" style="display: none;">
                    <label for="cancelReason"><strong>Cancel Reason:</strong></label>
                    <textarea id="cancelReason" class="form-control" rows="3" placeholder="Enter reason for cancellation"></textarea>
                </div>
            </div>

            <!-- Total + Footer -->
            <div class="px-4 pb-2">
                <div class="d-flex justify-content-between align-items-center" style="border-top: 2px solid #DB5C79; padding-top: 10px;">
                    <h5>Total:</h5>
                    <h5 class="fw-bold" id="checkoutTotal">₱0.00</h5>
                </div>
            </div>

            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary" style="background-color: #C04A67;" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" style="background-color: #6CCF54; border-color: #6CCF54;" onclick="confirmOrder()">Confirm Order</button>
            </div>
        </div>
    </div>
</div>


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
        height: 250px;
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

    .custom-card-img-square {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 0.5rem;
        /* optional: for rounded corners */
        display: block;
        margin: 0 auto;
        /* center horizontally */
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
            const response = await fetch('orders_latest_id_get.php');
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

    async function addToCart(genericName, brandName, itemPrice, group, inventoryId, imageBase64, milligram, dosageForm, quantity) {
        await fetchLatestOrderId(); // Fetch updated Order ID before adding to cart

        console.log("Adding to cart:", {
            genericName,
            brandName,
            itemPrice,
            group,
            inventoryId,
            milligram,
            dosageForm,
            quantity
        });

        const itemName = `${genericName} ${brandName}`;
        const stockElements = document.querySelectorAll(`#stock-${inventoryId}`);
        console.log("Stock elements found:", stockElements);

        if (stockElements.length === 0) {
            console.error(`No stock element found for inventoryId: ${inventoryId}`);
            return; // Exit the function if no stock element is found
        }

        const originalQuantity = parseInt(stockElements[0].innerText);
        console.log("Original quantity:", originalQuantity);

        if (!cart[itemName]) {
            cart[itemName] = {
                quantity: 0,
                price: parseFloat(itemPrice),
                inventoryId: inventoryId,
                medicineGroup: group,
                originalQuantity: originalQuantity,
                image: imageBase64, // Add the image to the cart object
                milligram: milligram,
                dosageForm: dosageForm,
                stockQuantity: quantity,
                genericName: genericName,
                brandName: brandName,
                group: group
            };
        }
        if (cart[itemName].quantity < originalQuantity) {
            cart[itemName].quantity++;
            updateStock(inventoryId, -1);
            updateCart();

            // Update the modal's stock display
            const modalStockElement = document.getElementById('modalStock');
            if (modalStockElement) {
                const currentStock = parseInt(modalStockElement.textContent.split(': ')[1]);
                modalStockElement.textContent = `Quantity in Stock: ${currentStock - 1}`;
            }
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

            // Add the cart item with the image at the top
            cartItems.innerHTML += `
        <div class="cart-item">
            <div class="item-image">
                <img src="data:image/jpeg;base64,${item.image}" alt="${itemName}" class="img-fluid mb-2" style="width: 100%; max-width: 100px; height: auto; object-fit: cover; border-radius: 0.5rem;" />
            </div>
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
        <button type="button" class="btn btn-outline-primary" id="print-receipt-btn" data-bs-toggle="modal" data-bs-target="#checkoutModal" onclick="populateCheckoutModal()">Check Out</button>
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

    function updateStock(inventoryId, change) {
        const stockElements = document.querySelectorAll(`#stock-${inventoryId}`);
        stockElements.forEach(stockElement => {
            const currentStock = parseInt(stockElement.innerText);
            stockElement.innerText = currentStock + change;
        });
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

    function clearCart() {
        for (const itemName in cart) {
            updateStock(cart[itemName].inventoryId, cart[itemName].quantity);
        }
        cart = {}; // Clear the cart object
        updateCart();
    }

    function showDrugDetails(imageBase64, genericName, brandName, milligram, dosageForm, group, quantity, price, inventoryId) {
        const imageSrc = `data:image/jpeg;base64,${imageBase64}`;

        document.getElementById('modalDrugImage').src = imageSrc;
        document.getElementById('modalDrugName').textContent = `${genericName} ${brandName}`;
        document.getElementById('modalDosage').textContent = `Dosage: ${milligram} mg - ${dosageForm}`;
        document.getElementById('modalGroup').textContent = `Group: ${group}`;

        // Calculate the updated stock based on the cart
        const itemName = `${genericName} ${brandName}`;
        const cartQuantity = cart[itemName] ? cart[itemName].quantity : 0;
        const updatedQuantity = quantity - cartQuantity;

        document.getElementById('modalStock').textContent = `Quantity in Stock: ${updatedQuantity}`;
        document.getElementById('modalPrice').textContent = `Price: ₱${price}`;

        // Debugging log to ensure inventoryId is correct
        console.log("Inventory ID passed to modal:", inventoryId);

        // Dynamically set the onclick handler for the Add to Cart button
        const addToCartBtn = document.getElementById('modalAddToCartBtn');
        addToCartBtn.onclick = function() {
            addToCart(genericName, brandName, price, group, inventoryId, imageBase64, milligram, dosageForm, quantity);
        };

        const modal = new bootstrap.Modal(document.getElementById('drugDetailModal'));
        modal.show();
    }

    function populateCheckoutModal() {
        const orderSummary = document.getElementById('order-summary');
        const orderDate = document.getElementById('orderDate');
        const estimatedDelivery = document.getElementById('estimatedDelivery');
        const checkoutTotal = document.getElementById('checkoutTotal');

        orderSummary.innerHTML = '';
        let total = 0;
        const cartItems = Object.keys(cart);

        cartItems.forEach((itemName, index) => {
            const item = cart[itemName];
            const itemTotal = item.quantity * item.price;
            total += itemTotal;

            const borderStyle = index < cartItems.length - 1 ? 'border-bottom: 1px solid #6CCF54;' : '';

            orderSummary.innerHTML += `
        <div class="d-flex align-items-center mb-3" style="${borderStyle} padding-bottom: 10px;">
            <div style="flex: 1; text-align: center;">
                <img src="data:image/jpeg;base64,${item.image}" alt="${itemName}" class="img-fluid" style="width: 100px; height: auto; object-fit: cover; border-radius: 0.5rem;" />
            </div>
            <div style="flex: 3; padding-left: 15px;">
                <h5 style="margin: 0 0 10px;">${itemName}</h5>
                <p class="mb-1">Quantity: ${item.quantity}</p>
                <p class="mb-1">Price per Unit: ₱${item.price.toFixed(2)}</p>
                <p class="mb-1 fw-bold">Total: ₱${itemTotal.toFixed(2)}</p>
            </div>
        </div>
        `;
        });

        checkoutTotal.textContent = `₱${total.toFixed(2)}`;

        // Order date & delivery
        const now = new Date();
        orderDate.textContent = now.toLocaleString();

        const delivery = new Date(now);
        delivery.setDate(now.getDate() + 3);
        estimatedDelivery.textContent = delivery.toLocaleDateString();
    }

    async function confirmOrder() {
    const address = document.getElementById('address').value;
    const phoneNumber = document.getElementById('phoneNumber').value;

    if (!address || !phoneNumber) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: 'Please fill in all required fields.',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        return;
    }

    const orderData = {
        cart: cart,
        address: address,
        phoneNumber: phoneNumber,
        orderDateTime: new Date().toISOString(),
        estimatedDelivery: new Date(new Date().setDate(new Date().getDate() + 3)).toISOString()
    };

    try {
        const response = await fetch('confirm_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderData)
        });

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Order confirmed successfully!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            cart = {}; // Clear the cart
            updateCart(); // Update the cart UI

            // Close the modal properly
            const checkoutModal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
            if (checkoutModal) {
                checkoutModal.hide();
            }

            // Remove the modal backdrop manually if it persists
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
        } else {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Failed to confirm order: ' + result.message,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }
    } catch (error) {
        console.error("Error confirming order:", error);
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'An error occurred while confirming the order.',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    }
}
</script>