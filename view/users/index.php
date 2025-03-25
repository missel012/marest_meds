<?php
include("./includes/header.php");
include("./includes/topbar.php"); // Remove messages icon in topbar
include("./includes/sidebar.php");
include("../../db/config.php"); // Include database configuration

// Fetch total number of medicines
$medicine_query = "SELECT COUNT(*) AS total_medicines FROM inventory";
$medicine_result = mysqli_query($conn, $medicine_query);
$medicine_data = mysqli_fetch_assoc($medicine_result);
$total_medicines = $medicine_data['total_medicines'];

// Fetch number of medicines with quantity less than 30
$low_stock_query = "SELECT inventoryId, genericName, brandName, milligram, dosageForm, quantity, price FROM inventory WHERE quantity < 30";
$low_stock_result = mysqli_query($conn, $low_stock_query);
$low_stock_medicines = [];
while ($row = mysqli_fetch_assoc($low_stock_result)) {
    $low_stock_medicines[] = $row;
}

// Determine current shift
date_default_timezone_set('Asia/Manila'); // Set the timezone
$current_hour = date('H');
if ($current_hour >= 8 && $current_hour < 12) {
    $current_shift = 'Day';
} elseif ($current_hour >= 12 && $current_hour < 17) {
    $current_shift = 'Afternoon';
} elseif ($current_hour >= 17 && $current_hour < 21) {
    $current_shift = 'Night';
} else {
    $current_shift = 'None';
}

// Fetch number of staff on current shift
$staff_query = "SELECT COUNT(*) AS staff_on_shift FROM staff WHERE shifts = '$current_shift'";
$staff_result = mysqli_query($conn, $staff_query);
$staff_data = mysqli_fetch_assoc($staff_result);
$staff_on_shift = $staff_data['staff_on_shift'];

// Fetch total number of orders
$total_orders_query = "SELECT COUNT(orderId) AS total_orders FROM `order`";
$total_orders_result = mysqli_query($conn, $total_orders_query);
$total_orders_data = mysqli_fetch_assoc($total_orders_result);
$total_orders = $total_orders_data['total_orders'];

// Fetch orders data for the graph
$query = "SELECT DATE(datetime) as order_date, COUNT(orderId) as total_orders FROM `order` GROUP BY DATE(datetime)";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}
?>

<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<div class="row">
  <!-- Info Cards -->
  <div class="col-lg-4 col-md-6">
    <div class="card info-card low-stock-card" style="border: 3px solid #52e42e;">
      <div class="card-body text-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center mx-auto">
          <i class="bi bi-exclamation-triangle"></i>
        </div>
        <h6 class="mt-3">Low on Stock</h6>
        <h2><?php echo count($low_stock_medicines); ?></h2>
        <button type="button" class="btn btn-custom mt-3" data-bs-toggle="modal" data-bs-target="#lowStockModal">View Details <i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6">
    <div class="card info-card inventory-card">
      <div class="card-body text-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center mx-auto">
          <i class="bi bi-capsule"></i>
        </div>
        <h6 class="mt-3">Medicines Available</h6>
        <h2><?php echo $total_medicines; ?></h2>
        <a href="inventory.php" class="btn btn-custom mt-3" style="background-color: #7ddf64;">Visit Inventory <i class="bi bi-chevron-right"></i></a>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6">
    <div class="card info-card total-orders-card">
      <div class="card-body text-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center mx-auto">
          <i class="bi bi-cart"></i>
        </div>
        <h6 class="mt-3">Total Orders Made</h6>
        <h2><?php echo $total_orders; ?></h2>
        <a href="prescriptionOrders.php" class="btn btn-custom mt-3" style="background-color: #7ddf64;">View Orders <i class="bi bi-chevron-right"></i></a>
      </div>
    </div>
  </div>
</div>

<!-- Low Stock Modal -->
<div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lowStockModalLabel">Low on Stock Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Generic Name</th>
              <th>Brand Name</th>
              <th>Milligram</th>
              <th>Dosage Form</th>
              <th>Quantity</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($low_stock_medicines as $medicine): ?>
              <tr>
                <td><?php echo $medicine['genericName']; ?></td>
                <td><?php echo $medicine['brandName']; ?></td>
                <td><?php echo $medicine['milligram']; ?></td>
                <td><?php echo $medicine['dosageForm']; ?></td>
                <td><?php echo $medicine['quantity']; ?></td>
                <td><?php echo $medicine['price']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card" style="border: 3px solid #DB5C79; border-radius: 15px;">
      <div class="card-body">
        <h5 class="card-title">Sales Performance</h5>
        <div id="areaChart"></div>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            const ordersData = <?= json_encode($orders) ?>;
            const labels = ordersData.map(order => order.order_date);
            const data = ordersData.map(order => order.total_orders);

            new ApexCharts(document.querySelector("#areaChart"), {
              series: [{
                name: "Total Orders",
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
                curve: 'straight',
                colors: ['#DB5C79']
              },
              fill: {
                colors: ['#DB5C79', '#FFDEE6']
              },
              subtitle: {
                text: 'Order Movements',
                align: 'left'
              },
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
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card" style="border: 3px solid #DB5C79; border-radius: 15px;">
      <div class="card-body">
        <h5 class="card-title">Calendar</h5>
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const calendarElement = document.getElementById('calendar');
    const currentDate = new Date();
    const currentDay = currentDate.getDate();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();

    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    let calendarHTML = '<table class="table table-bordered"><thead><tr>';

    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    daysOfWeek.forEach(day => {
      calendarHTML += `<th>${day}</th>`;
    });

    calendarHTML += '</tr></thead><tbody><tr>';

    const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
    for (let i = 0; i < firstDayOfMonth; i++) {
      calendarHTML += '<td></td>';
    }

    for (let day = 1; day <= daysInMonth; day++) {
      if ((day + firstDayOfMonth - 1) % 7 === 0) {
        calendarHTML += '</tr><tr>';
      }
      if (day === currentDay) {
        calendarHTML += `<td style="background-color: #DB5C79; color: white;">${day}</td>`;
      } else {
        calendarHTML += `<td>${day}</td>`;
      }
    }

    const lastDayOfMonth = new Date(currentYear, currentMonth, daysInMonth).getDay();
    for (let i = lastDayOfMonth + 1; i < 7; i++) {
      calendarHTML += '<td></td>';
    }

    calendarHTML += '</tr></tbody></table>';
    calendarElement.innerHTML = calendarHTML;
  });
</script>

<?php
include("./includes/footer.php");
?>

<style>
/* Dashboard Info Cards */
.info-card {
  padding: 20px;
  border-radius: 15px; /* Changed to make corners rounded */
  border: 3px solid #52e42e; /* Border color same as buttons */
  box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
  margin-bottom: 20px;
}

.info-card .card-icon {
  font-size: 32px;
  line-height: 0;
  width: 64px;
  height: 64px;
  flex-shrink: 0;
  flex-grow: 0;
  background: #f6f6fe;
  color:rgb(233, 75, 75);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-card h5 {
  font-size: 18px;
  font-weight: 600;
  color: #012970;
}

.info-card h6 {
  font-size: 24px;
  font-weight: 700;
  color: #012970;
}

.info-card .btn {
  margin-top: 10px;
  font-size: 14px;
  font-weight: 600;
  color: #fff;
  background: #52e42e;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
}

.info-card .btn:hover {
  background: #5bbf4a;
}

  </style>