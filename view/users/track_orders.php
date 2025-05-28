<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php");
?>
<?php

$userId = 1; // Hardcoded for now. Change dynamically based on session/login.

// Fetch orders
$ordersQuery = "
    SELECT t.trackId, t.orderId, t.status, t.orderDateTime, t.estimatedDelivery, t.cancelReason
    FROM track_order t
    WHERE t.userId = $userId
    ORDER BY t.orderDateTime DESC
";
$ordersResult = $conn->query($ordersQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Orders</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f9f9f9; }
        .order-box { background: #fff; padding: 15px; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; text-transform: capitalize; }
        .ordered { background: orange; color: white; }
        .processing { background: gold; }
        .shipping { background: blue; color: white; }
        .delivered { background: green; color: white; }
        .completed { background: darkgreen; color: white; }
        .cancelled, .rejected { background: red; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h2>🛒 Your Orders</h2>

<?php
while ($order = $ordersResult->fetch_assoc()) {
    $trackId = $order['trackId'];
    echo "<div class='order-box'>";
    echo "<strong>Order #{$order['orderId']}</strong><br>";
    echo "Date: " . date("M d, Y h:i A", strtotime($order['orderDateTime'])) . "<br>";
    echo "Estimated Delivery: " . ($order['estimatedDelivery'] ?: "N/A") . "<br>";
    echo "Status: <span class='status-badge {$order['status']}'>{$order['status']}</span><br>";
    
    if ($order['cancelReason']) {
        echo "<strong>❌ Cancel Reason:</strong> " . htmlspecialchars($order['cancelReason']) . "<br>";
    }

    // Fetch items
    $itemsQuery = "
        SELECT oi.genericName, oi.brandName, oi.milligram, oi.dosageForm, oi.quantity, oi.price, oi.total
        FROM order_items oi
        WHERE oi.orderId = {$order['orderId']}
    ";
    $itemsResult = $conn->query($itemsQuery);
    
    echo "<table>";
    echo "<tr><th>Generic</th><th>Brand</th><th>Dosage</th><th>Form</th><th>Qty</th><th>Price</th><th>Total</th></tr>";
    while ($item = $itemsResult->fetch_assoc()) {
        echo "<tr>
            <td>{$item['genericName']}</td>
            <td>{$item['brandName']}</td>
            <td>{$item['milligram']} mg</td>
            <td>{$item['dosageForm']}</td>
            <td>{$item['quantity']}</td>
            <td>₱" . number_format($item['price'], 2) . "</td>
            <td>₱" . number_format($item['total'], 2) . "</td>
        </tr>";
    }
    echo "</table>";
    echo "</div>";
}
?>

</body>
</html>


<?php
include("./includes/footer.php");
?>
