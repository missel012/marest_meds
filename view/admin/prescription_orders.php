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
    <button class="btn btn-primary w-100" id="modifyOrder">Modify Orders</button>
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
        <h5 class="card-title">Orders</h5>
        <p>Total Orders: <?= count($all_orders) ?></p>

        <!-- Order Table -->
        <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Date and Time</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($all_orders as $order) : ?>
                <tr>
                  <td>ORDER-00<?= htmlspecialchars($order['orderId']) ?></td>
                  <td><?= htmlspecialchars($order['datetime']) ?></td>
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