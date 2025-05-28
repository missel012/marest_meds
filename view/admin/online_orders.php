<?php
require_once __DIR__ . '/../../auth/authentication.php';
requireRole('admin');
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");

// Placeholder orders array
$orders = [
    [
        'orderId' => 1,
        'datetime' => '2024-06-01 10:30:00',
        'customer_email' => 'john@example.com',
        'firstName' => 'John',
        'lastName' => 'Doe',
        'status' => 'Pending',
        'items' => [
            ['genericName' => 'Paracetamol', 'brandName' => 'Biogesic', 'quantity' => 2],
            ['genericName' => 'Ibuprofen', 'brandName' => 'Advil', 'quantity' => 1],
        ]
    ],
    [
        'orderId' => 2,
        'datetime' => '2024-06-02 14:15:00',
        'customer_email' => 'jane@example.com',
        'firstName' => 'Jane',
        'lastName' => 'Smith',
        'status' => 'In Transit',
        'items' => [
            ['genericName' => 'Cetirizine', 'brandName' => 'Allerta', 'quantity' => 3],
        ]
    ],
    [
        'orderId' => 3,
        'datetime' => '2024-06-03 09:00:00',
        'customer_email' => 'alice@example.com',
        'firstName' => 'Alice',
        'lastName' => 'Brown',
        'status' => 'Delivered',
        'items' => [
            ['genericName' => 'Loperamide', 'brandName' => 'Imodium', 'quantity' => 1],
            ['genericName' => 'Amoxicillin', 'brandName' => 'Amoxil', 'quantity' => 2],
        ]
    ],
];

// Handle status update (simulate in session for demo)
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['online_orders'])) {
    $_SESSION['online_orders'] = $orders;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    foreach ($_SESSION['online_orders'] as &$order) {
        if ($order['orderId'] == $_POST['order_id']) {
            $order['status'] = $_POST['status'];
            break;
        }
    }
    unset($order);
    header("Location: online_orders.php");
    exit();
}
$orders = $_SESSION['online_orders'];
?>

<div class="pagetitle">
  <h1>Online Orders</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active">Online Orders</li>
    </ol>
  </nav>
</div>

<div class="card" style="border: 3px solid #DB5C79; border-radius: 15px;">
  <div class="card-body">
    <h5 class="card-title" style="color:#DB5C79; font-weight:bold;">Track & Update Online Orders</h5>
    <div class="table-responsive">
      <table class="table align-middle" style="border-radius: 10px; overflow: hidden;">
        <thead style="background: #DB5C79; color: #fff;">
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
          <tr style="background: #fff;">
            <td style="color:#DB5C79; font-weight:bold;">ORDER-00<?= htmlspecialchars($order['orderId']) ?></td>
            <td>
              <span style="color:#5AB94A; font-weight:bold;"><?= htmlspecialchars(($order['firstName'] ?? '') . ' ' . ($order['lastName'] ?? '')) ?></span><br>
              <small style="color:#888;"><?= htmlspecialchars($order['customer_email']) ?></small>
            </td>
            <td>
              <?php if (!empty($order['items'])): ?>
                <details>
                  <summary style="color:#5AB94A; cursor:pointer;"><?= count($order['items']) ?> item(s)</summary>
                  <ul style="padding-left: 1.2em;">
                    <?php foreach ($order['items'] as $item): ?>
                      <li><?= htmlspecialchars($item['genericName'] . ' ' . $item['brandName']) ?> <span style="color:#DB5C79;">(x<?= $item['quantity'] ?>)</span></li>
                    <?php endforeach; ?>
                  </ul>
                </details>
              <?php else: ?>
                <span style="color:#DB5C79;">No items</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($order['datetime']) ?></td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= $order['orderId'] ?>">
                <select name="status" class="form-select form-select-sm" style="width:auto;display:inline-block; border:1.5px solid #DB5C79; color:#DB5C79; font-weight:bold;">
                  <?php
                  $statuses = ['Pending', 'In Transit', 'On the Way', 'Delivered'];
                  foreach ($statuses as $status) {
                    $selected = (strcasecmp($order['status'], $status) === 0) ? 'selected' : '';
                    $color = $status === 'Delivered' ? '#5AB94A' : ($status === 'Pending' ? '#DB5C79' : '#DB5C79');
                    echo "<option value=\"$status\" $selected style=\"color:$color;\">$status</option>";
                  }
                  ?>
                </select>
            </td>
            <td>
                <button type="submit" class="btn btn-sm" style="background:#5AB94A; color:#fff; font-weight:bold; border:none;">Save</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<style>
/* Table row hover */
.table tbody tr:hover {
  background-color: #FFF0F5 !important;
}
/* Details summary arrow color */
details summary {
  outline: none;
}
details[open] summary {
  color: #DB5C79;
}
/* Card shadow */
.card {
  box-shadow: 0 4px 16px rgba(219,92,121,0.08), 0 1.5px 4px rgba(90,185,74,0.08);
}
</style>

<?php include("./includes/footer.php"); ?>
