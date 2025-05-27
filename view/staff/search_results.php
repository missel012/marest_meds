<?php
include("../../dB/config.php");
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [
    'Inventory' => [],

    'Prescriptions' => []
];

if ($query !== '') {
    $safe = mysqli_real_escape_string($conn, $query);

    // Inventory
    $invSql = "SELECT * FROM inventory WHERE genericName LIKE '%$safe%' OR brandName LIKE '%$safe%'";
    $invRes = mysqli_query($conn, $invSql);
    while ($row = mysqli_fetch_assoc($invRes)) {
        $results['Inventory'][] = $row;
    }


    // Prescriptions
    $orderSql = "SELECT * FROM order_items WHERE genericName LIKE '%$safe%' OR brandName LIKE '%$safe%' OR dosageForm LIKE '%$safe%' OR `group` LIKE '%$safe%'";
    $orderRes = mysqli_query($conn, $orderSql);
    while ($row = mysqli_fetch_assoc($orderRes)) {
        $results['Prescriptions'][] = $row;
    }
}
?>

<div class="pagetitle">
  <h1>Search</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active">Search Results</li>
    </ol>
  </nav>
</div>

<div class="container mt-4">
  <h4>
    Search Results for 
    <span class="fw-bold" style="color: #4CAF50;">"<?= htmlspecialchars($query) ?>"</span>
  </h4>

  <div class="mt-3 mb-4 d-flex flex-wrap gap-2">
    <button class="btn btn-outline-success active-tab" onclick="filterTab('all')">All</button>
    <button class="btn btn-outline-success" onclick="filterTab('inventory')">Inventory (<?= count($results['Inventory']) ?>)</button>
    <button class="btn btn-outline-success" onclick="filterTab('prescriptions')">Prescriptions (<?= count($results['Prescriptions']) ?>)</button>
  </div>

  <div id="results-container">
    <?php foreach ($results as $key => $data): 
      $tabClass = strtolower($key) . "-tab";
      $title = ucfirst($key) . " Results";
    ?>
    <div class="tab-group <?= $tabClass ?>">
      <div class="card mb-4 custom-card-border">
        <div class="card-body">
          <h5 class="card-title"><?= $title ?></h5>
          <?php if (empty($data)): ?>
            <p class="text-muted fst-italic">No <?= strtolower($key) ?> results found.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <?php foreach (array_keys($data[0]) as $col): ?>
                      <th><?= htmlspecialchars(ucwords(str_replace("_", " ", $col))) ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data as $row): ?>
                    <tr>
                      <?php foreach ($row as $val): ?>
                        <td><?= htmlspecialchars($val) ?></td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
function filterTab(tab) {
  document.querySelectorAll(".tab-group").forEach(e => e.style.display = "none");
  if (tab === 'all') {
    document.querySelectorAll(".tab-group").forEach(e => e.style.display = "block");
  } else {
    document.querySelectorAll(`.${tab}-tab`).forEach(e => e.style.display = "block");
  }
  document.querySelectorAll(".btn-outline-success").forEach(btn => btn.classList.remove("active-tab"));
  event.target.classList.add("active-tab");
}
</script>

<style>
/* Refined green to match dashboard theme */
.active-tab {
  background-color: #5bbf4a !important;
  border-color: #5bbf4a !important;
  color: white !important;
}
.btn-outline-success {
  border-color: #5bbf4a;
  color: #5bbf4a;
}
.btn-outline-success:hover {
  background-color: #5bbf4a;
  color: white;
}

.card-title {
  font-size: 20px;
  margin-bottom: 15px;
}

.custom-card-border {
  border: 3px solid #DB5C79;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.table thead th {
  background-color: #f9f9f9;
  font-weight: 600;
  color: #343a40;
}
</style>

<?php include("./includes/footer.php"); ?>
