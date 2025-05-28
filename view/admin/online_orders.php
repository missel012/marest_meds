<?php
require_once __DIR__ . '/../../auth/authentication.php';
requireRole('admin');
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../db/config.php");

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track_id'], $_POST['status'])) {
    $trackId = intval($_POST['track_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE track_order SET status = ? WHERE trackId = ?");
    $stmt->bind_param("si", $status, $trackId);
    $stmt->execute();
    $stmt->close();
    // Use JavaScript redirect to avoid header issues if output already started
    echo "<script>window.location.href='online_orders.php';</script>";
    exit();
}

// Fetch all online orders with user info
$query = "
    SELECT t.trackId, t.orderId, t.userId, t.status, t.orderDateTime, t.estimatedDelivery, t.cancelReason,
           u.firstName, u.lastName, u.email
    FROM track_order t
    LEFT JOIN users u ON t.userId = u.userId
    ORDER BY t.orderDateTime DESC
";
$result = $conn->query($query);

// Fetch order details for all track orders
$detailsQuery = "
    SELECT d.trackId, d.quantity, d.price, i.genericName, i.brandName
    FROM track_order_details d
    LEFT JOIN inventory i ON d.inventoryId = i.inventoryId
";
$detailsResult = $conn->query($detailsQuery);
$orderItems = [];
while ($row = $detailsResult->fetch_assoc()) {
    $orderItems[$row['trackId']][] = $row;
}
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
            <th>Est. Delivery</th>
            <th>Status</th>
            <th>Action</th>
            <th>Cancel Reason</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($order = $result->fetch_assoc()): ?>
          <tr style="background: #fff;">
            <td style="color:#DB5C79; font-weight:bold;">ORDER-00<?= htmlspecialchars($order['orderId']) ?></td>
            <td>
              <span style="color:#5AB94A; font-weight:bold;">
                <?= htmlspecialchars(($order['firstName'] ?? '') . ' ' . ($order['lastName'] ?? '')) ?>
              </span><br>
              <small style="color:#888;"><?= htmlspecialchars($order['email']) ?></small>
            </td>
            <td>
              <?php if (!empty($orderItems[$order['trackId']])): ?>
                <details>
                  <summary style="color:#5AB94A; cursor:pointer;"><?= count($orderItems[$order['trackId']]) ?> item(s)</summary>
                  <ul style="padding-left: 1.2em;">
                    <?php foreach ($orderItems[$order['trackId']] as $item): ?>
                      <li>
                        <?= htmlspecialchars($item['genericName'] . ' ' . $item['brandName']) ?>
                        <span style="color:#DB5C79;">(x<?= $item['quantity'] ?>)</span>
                        <span style="color:#5AB94A;">₱<?= number_format($item['price'], 2) ?></span>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </details>
              <?php else: ?>
                <span style="color:#DB5C79;">No items</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($order['orderDateTime']) ?></td>
            <td><?= htmlspecialchars($order['estimatedDelivery']) ?></td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="track_id" value="<?= $order['trackId'] ?>">
                <select name="status" class="form-select form-select-sm" style="width:auto;display:inline-block; border:1.5px solid #DB5C79; color:#DB5C79; font-weight:bold;">
                  <?php
                  $statuses = ['ordered','processing','shipping','delivered','completed','cancelled','rejected'];
                  foreach ($statuses as $status) {
                    $selected = (strcasecmp($order['status'], $status) === 0) ? 'selected' : '';
                    $color = $status === 'delivered' || $status === 'completed' ? '#5AB94A' : ($status === 'cancelled' || $status === 'rejected' ? '#DB5C79' : '#DB5C79');
                    echo "<option value=\"$status\" $selected style=\"color:$color;\">".ucfirst($status)."</option>";
                  }
                  ?>
                </select>
            </td>
            <td>
                <button type="submit" class="btn btn-sm" style="background:#5AB94A; color:#fff; font-weight:bold; border:none;">Save</button>
              </form>
            </td>
            <td>
              <?php if ($order['cancelReason']) {
                echo '<span style="color:#DB5C79;">' . htmlspecialchars($order['cancelReason']) . '</span>';
              } ?>
            </td>
          </tr>
        <?php endwhile; ?>
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
