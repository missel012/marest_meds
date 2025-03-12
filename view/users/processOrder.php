<?php
include("../../../dB/config.php");

$data = json_decode(file_get_contents('php://input'), true);

foreach ($data as $itemName => $item) {
    $inventoryId = $item['inventoryId'];
    $genericName = $item['genericName'];
    $brandName = $item['brandName'];
    $milligram = $item['milligram'];
    $dosageForm = $item['dosageForm'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $group = $item['group'];
    $total = $quantity * $price;

    // Insert order into order table
    $query = "INSERT INTO `order` (inventoryId, genericName, brandName, milligram, dosageForm, quantity, price, `group`, total, datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issisidss", $inventoryId, $genericName, $brandName, $milligram, $dosageForm, $quantity, $price, $group, $total);
    $stmt->execute();

    // Update inventory quantity
    $updateQuery = "UPDATE inventory SET quantity = quantity - ? WHERE inventoryId = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $quantity, $inventoryId);
    $stmt->execute();
}

echo json_encode(['status' => 'success']);
?>