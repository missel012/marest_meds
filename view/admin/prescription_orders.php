<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php"); // Ensure this file contains your database connection

// Fetch orders data for the graph
$query = "SELECT DATE(datetime) as orderDate, SUM(total) as totalRevenue FROM `order` GROUP BY orderDate ORDER BY orderDate DESC LIMIT 10";
$result = mysqli_query($conn, $query);

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

// Fetch all orders for the table
$query_all_orders = "SELECT * FROM `order` ORDER BY datetime DESC";
$result_all_orders = mysqli_query($conn, $query_all_orders);

$all_orders = [];
while ($row = mysqli_fetch_assoc($result_all_orders)) {
    $all_orders[] = $row;
}
?>

<div class="pagetitle">
  <h1>Prescription Orders</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Prescription Orders</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<div class="row g-3">
  <div class="col-md-4">
    <div class="form-floating">
      <input type="date" class="form-control" id="dateRange" placeholder="Date Range">
      <label for="dateRange">Date Range</label>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-floating">
      <select class="form-select" id="medicineGroup" aria-label="Select Medicine Group">
        <option selected>Select Group</option>
        <option value="analgesic">Analgesic</option>
        <option value="antibiotic">Antibiotic</option>
        <option value="antidiabetic">Antidiabetic</option>
        <option value="antihistamine">Antihistamine</option>
        <option value="antihypertensive">Antihypertensive</option>
        <option value="NSAID">NSAID</option>
      </select>
      <label for="medicineGroup">Medicine Group</label>
    </div>
  </div>

  <div class="col-md-4 d-flex align-items-end">
    <button class="btn btn-primary w-100" style="background: #DB5C79; border: none" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add Order Transaction</button>
  </div>
</div>

<div class="row mt-4"> <!-- Added margin-top class here -->

  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Sales Made</h5>

        <!-- Area Chart -->
        <div id="areaChart"></div>

        <script>
          document.addEventListener("DOMContentLoaded", () => {
            const ordersData = <?= json_encode($orders) ?>;
            const labels = ordersData.map(order => order.orderDate);
            const data = ordersData.map(order => order.totalRevenue);

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
                curve: 'straight'
              },
              subtitle: {
                text: 'Revenue Movements',
                align: 'left'
              },
              colors: ['#DB5C79'], // Changed color here
              labels: labels,
              xaxis: {
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
        <p>Total Orders: <?= count($all_orders) ?></p>

        <!-- Order Table -->
        <div id="ordersTableContainer" class="table-responsive" style="max-height: 350px; overflow-y: auto;">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Generic Name</th>
                <th scope="col">Brand Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total</th>
                <th scope="col">Date and Time</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($all_orders as $order) : ?>
                <tr>
                  <td>ORDER-00<?= htmlspecialchars($order['orderId']) ?></td>
                  <td><?= htmlspecialchars($order['genericName']) ?></td>
                  <td><?= htmlspecialchars($order['brandName']) ?></td>
                  <td><?= htmlspecialchars($order['quantity']) ?></td>
                  <td>₱<?= number_format($order['total'], 2) ?></td>
                  <td><?= htmlspecialchars($order['datetime']) ?></td>
                  <td>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteOrderModal" data-id="<?= $order['orderId'] ?>">
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
                <th scope="col">Quantity</th>
                <th scope="col">Total</th>
                <th scope="col">Date and Time</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($all_orders as $order) : ?>
                <tr>
                  <td>ORDER-00<?= htmlspecialchars($order['orderId']) ?></td>
                  <td><?= htmlspecialchars($order['genericName']) ?></td>
                  <td><?= htmlspecialchars($order['brandName']) ?></td>
                  <td><?= htmlspecialchars($order['quantity']) ?></td>
                  <td>₱<?= number_format($order['total'], 2) ?></td>
                  <td><?= htmlspecialchars($order['datetime']) ?></td>
                  <td>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteOrderModal" data-id="<?= $order['orderId'] ?>">
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addOrderModalLabel">Add Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addOrderForm">
          <div class="mb-3">
            <label for="genericName" class="form-label">Generic Name</label>
            <input type="text" class="form-control" id="genericName" name="genericName" required>
          </div>
          <div class="mb-3">
            <label for="brandName" class="form-label">Brand Name</label>
            <input type="text" class="form-control" id="brandName" name="brandName" required>
          </div>
          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
          </div>
          <div class="mb-3">
            <label for="total" class="form-label">Total</label>
            <input type="number" step="0.01" class="form-control" id="total" name="total" required>
          </div>
          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

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
  document.getElementById('addOrderForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('add_order.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('Error adding order: ' + (data.message || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error adding order: ' + error.message);
    });
  });

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