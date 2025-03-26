<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php"); // Ensure this file contains your database connection

// Get search input
$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Update query to filter by search term
$query = "
    SELECT 
        o.orderId, 
        o.datetime, 
        oi.orderItemId, 
        oi.inventoryId, 
        oi.genericName, 
        oi.brandName, 
        oi.milligram, 
        oi.dosageForm, 
        oi.quantity, 
        oi.price, 
        oi.group, 
        oi.total
    FROM 
        `order` o
    INNER JOIN 
        order_items oi 
    ON 
        o.orderId = oi.orderId
    WHERE 
        oi.genericName LIKE ? 
        OR oi.brandName LIKE ? 
        OR oi.group LIKE ?
    ORDER BY 
        o.datetime DESC, oi.orderItemId ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['orderId']]['datetime'] = $row['datetime'];
    $orders[$row['orderId']]['items'][] = $row;
}

// Fetch orders and calculate total revenue for each order
$query = "
    SELECT 
        DATE(o.datetime) AS order_date, 
        SUM(oi.total) AS total_revenue
    FROM 
        `order` o
    INNER JOIN 
        order_items oi 
    ON 
        o.orderId = oi.orderId
    GROUP BY 
        DATE(o.datetime)
    ORDER BY 
        DATE(o.datetime) ASC
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$graphData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $graphData[] = [
        'order_date' => $row['order_date'],
        'total_revenue' => $row['total_revenue']
    ];
}

// Fetch inventory items grouped by category
$query = "SELECT * FROM inventory ORDER BY `group` ASC, inventoryId ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$inventory = [];
while ($row = mysqli_fetch_assoc($result)) {
    $inventory[$row['group']][] = $row;
}

// Fetch the last order ID from the database
$orderQuery = "SELECT MAX(orderId) as lastOrderId FROM `order`";
$orderResult = mysqli_query($conn, $orderQuery);
$orderRow = mysqli_fetch_assoc($orderResult);
$nextOrderId = $orderRow['lastOrderId'] + 1;

// Pass graph data to JavaScript
?>
<script>
    const graphData = <?php echo json_encode($graphData); ?>;
</script>

<div class="pagetitle">
  <h1>Prescription Orders</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Prescription Orders</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<div class="row g-3 align-items-end">
  <form method="GET" action="prescription_orders.php" class="col-md-8">
    <div class="input-group">
      <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
      <button type="submit" class="btn btn-primary" style="background: #DB5C79; border: none">
        <i class="bi bi-search"></i> <!-- Icon inside the search bar -->
      </button>
    </div>
  </form>

  <div class="col-md-4">
    <button class="btn btn-primary w-100" style="background: #DB5C79; border: none" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add Order Transaction</button>
  </div>
</div>

<div class="row mt-4"> <!-- Added margin-top class here -->

  <div class="col-lg-6">
    <div class="card" style="border: 3px solid #DB5C79; border-radius: 15px;">
      <div class="card-body">
        <h5 class="card-title">Sales Performance</h5>

        <!-- Area Chart -->
        <div id="areaChart"></div>

        <script>
          document.addEventListener("DOMContentLoaded", () => {
            const labels = graphData.map(data => data.order_date);
            const data = graphData.map(data => data.total_revenue);

            new ApexCharts(document.querySelector("#areaChart"), {
              series: [{
                name: "Total Revenue",
                data: data
              }],
              chart: {
                type: 'area',
                height: 350,
                zoom: {
                  enabled: false
                }
              },
              dataLabels: {
                enabled: false
              },
              stroke: {
                curve: 'smooth'
              },
              subtitle: {
                text: 'Revenue Movements',
                align: 'left'
              },
              colors: ['#DB5C79'], // Changed color here
              xaxis: {
                categories: labels,
                type: 'datetime',
              },
              yaxis: {
                opposite: true
              },
              legend: {
                horizontalAlign: 'left'
              }
            }).render();
          });
        </script>
        <!-- End Area Chart -->

      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Orders</h5>
          <button id="expandTableButton" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#expandedOrdersModal">
            <i class="bi bi-arrows-fullscreen"></i> Expand
          </button>
        </div>
        <p>Total Orders: <?= count($orders) ?></p>

        <!-- Order Table -->
        <div id="ordersTableContainer" class="table-responsive" style="max-height: 350px; overflow-y: auto;">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Generic Name</th>
                <th scope="col">Brand Name</th>
                <th scope="col">Milligram</th>
                <th scope="col">Dosage Form</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Group</th>
                <th scope="col">Total</th>
                <th scope="col">Date and Time</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $orderId => $order) : ?>
                <tr>
                  <td>ORDER-00<?= htmlspecialchars($orderId) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['genericName']) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['brandName']) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['milligram']) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['dosageForm']) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['quantity']) ?></td>
                  <td>₱<?= number_format($order['items'][0]['price'], 2) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['group']) ?></td>
                  <td>₱<?= number_format($order['items'][0]['total'], 2) ?></td>
                  <td><?= htmlspecialchars($order['datetime']) ?></td>
                  <td>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteOrderModal" data-id="<?= $orderId ?>">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!-- End Order Table -->

      </div>
    </div>
  </div>

</div>

<!-- Expanded Orders Modal -->
<div class="modal fade" id="expandedOrdersModal" tabindex="-1" aria-labelledby="expandedOrdersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expandedOrdersModalLabel">Expanded Orders Table</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Generic Name</th>
                <th scope="col">Brand Name</th>
                <th scope="col">Milligram</th>
                <th scope="col">Dosage Form</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Group</th>
                <th scope="col">Total</th>
                <th scope="col">Date and Time</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $orderId => $order) : ?>
                <tr>
                  <td>ORDER-00<?= htmlspecialchars($orderId) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['genericName']) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['brandName']) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['milligram']) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['dosageForm']) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['quantity']) ?></td>
                  <td>₱<?= number_format($order['items'][0]['price'], 2) ?></td>
                  <td><?= htmlspecialchars($order['items'][0]['group']) ?></td>
                  <td>₱<?= number_format($order['items'][0]['total'], 2) ?></td>
                  <td><?= htmlspecialchars($order['datetime']) ?></td>
                  <td>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteOrderModal" data-id="<?= $orderId ?>">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Add Order Modal -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderModalLabel">Add Order Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                                                        </button>
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
                                                        </button>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <!-- End Left Card -->

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
                        </div>
                        <!-- End Right Card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Add Order Modal -->

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
    .logo {
        margin: 0 auto;
        display: flex;
        justify-content: center;
        margin-top: 15px;
        /* Add top margin to match the space below the Pay Now button */
    }
</style>

<script>
  let cart = {};

  function addToCart(genericName, brandName, price, group, inventoryId) {
    const itemName = `${genericName} ${brandName}`;
    const stockElement = document.getElementById(`stock-${inventoryId}`);
    const stock = parseInt(stockElement.innerText);

    if (!cart[itemName]) {
      cart[itemName] = { quantity: 0, price, inventoryId, group, stock };
    }

    if (cart[itemName].quantity < stock) {
      cart[itemName].quantity++;
      stockElement.innerText = stock - 1;
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
      const itemTotal = item.quantity * item.price;
      total += itemTotal;

      cartItems.innerHTML += `
        <div class="cart-item">
          <span>${itemName} - ₱${itemTotal.toFixed(2)} (${item.quantity})</span>
          <button class="remove-btn" onclick="removeFromCart('${itemName}')">
            <i class="bi bi-trash-fill"></i>
          </button>
        </div>
      `;
    }

    totalAmount.innerText = `₱${total.toFixed(2)}`;
    cartSummary.style.display = total > 0 ? 'block' : 'none';
  }

  function removeFromCart(itemName) {
    const item = cart[itemName];
    const stockElement = document.getElementById(`stock-${item.inventoryId}`);
    stockElement.innerText = parseInt(stockElement.innerText) + item.quantity;
    delete cart[itemName];
    updateCart();
  }

  function clearCart() {
    for (const itemName in cart) {
      const item = cart[itemName];
      const stockElement = document.getElementById(`stock-${item.inventoryId}`);
      stockElement.innerText = parseInt(stockElement.innerText) + item.quantity;
    }
    cart = {};
    updateCart();
  }

  function showReceipt() {
    console.log('Receipt:', cart);
    clearCart();
  }
</script>

<!-- Delete Order Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteOrderModalLabel">Delete Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this order?</p>
        <form id="deleteOrderForm">
          <input type="hidden" id="deleteOrderId" name="orderId">
          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('deleteOrderForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('delete_order.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('Error deleting order: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error deleting order: ' + error.message);
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    const deleteOrderModal = document.getElementById('deleteOrderModal');

    deleteOrderModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const orderId = button.getAttribute('data-id');
      document.getElementById('deleteOrderId').value = orderId;
    });
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const dateRangePicker = new Litepicker({
      element: document.getElementById('dateRange'),
      singleMode: false,
      format: 'YYYY-MM-DD'
    });
  });
</script>

<?php
include("./includes/footer.php");
?>