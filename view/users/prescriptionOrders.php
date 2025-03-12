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
                                            <button type="button" class="btn custom-card mt-2 mb-2" onclick="addToCart('<?php echo $item['genericName']; ?>', '<?php echo $item['brandName']; ?>', <?php echo $item['price']; ?>, '', <?php echo $item['inventoryId']; ?>)">
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
                                            <button type="button" class="btn custom-card mt-2 mb-2" onclick="addToCart('<?php echo $item['genericName']; ?>', '<?php echo $item['brandName']; ?>', <?php echo $item['price']; ?>, '', <?php echo $item['inventoryId']; ?>)">
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
        width: 100%;
        height: 130px; /* Set a fixed height */
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
        background-color: #5bbd4a;
        color: white; /* Ensure text color remains white */
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
        background-color: #5bbd4a;
        border-color: #5bbd4a;
        color: white; /* Ensure text color remains white */
    }

    /* Scrollable tabs */
    #pills-tab {
        overflow-x: auto; 
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    #pills-tab::-webkit-scrollbar {
        display: none; /* Hide scrollbar */
    }

    .nav-pills .nav-link {
        display: inline-block;
        white-space: nowrap; /* Ensure tabs are in one row */
    }

    .nav-pills {
        flex-wrap: nowrap; /* Prevent tabs from wrapping to the next line */
    }
</style>

<script>
    let cart = {};

    function addToCart(genericName, brandName, itemPrice, itemImage, inventoryId) {
        const itemName = `${genericName} ${brandName}`;
        const stockElement = document.getElementById(`stock-${inventoryId}`);
        const originalQuantity = parseInt(stockElement.innerText);
        
        if (!cart[itemName]) {
            cart[itemName] = { quantity: 0, price: itemPrice, image: itemImage, inventoryId: inventoryId, originalQuantity: originalQuantity };
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

        totalAmount.innerText = `₱${total.toFixed(2)}`;
        cartSummary.style.display = total > 0 ? 'block' : 'none';
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
        const stockElement = document.getElementById(`stock-${inventoryId}`);
        const currentStock = parseInt(stockElement.innerText);
        stockElement.innerText = currentStock + change;
    }

    document.getElementById('pay-now-btn').addEventListener('click', function() {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'processOrder.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Order placed successfully!');
                cart = {};
                updateCart();
            }
        };
        xhr.send(JSON.stringify(cart));
    });
</script>