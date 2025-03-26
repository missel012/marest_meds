<?php
include("../../dB/config.php");

$orderQuery = "SELECT MAX(orderId) as lastOrderId FROM `order`";
$orderResult = mysqli_query($conn, $orderQuery);
$orderRow = mysqli_fetch_assoc($orderResult);
$nextOrderId = $orderRow['lastOrderId'] + 1;

echo json_encode(['success' => true, 'nextOrderId' => $nextOrderId]);
?>
