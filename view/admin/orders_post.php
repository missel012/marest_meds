<?php
include("../../dB/config.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$response = ["success" => false, "message" => "Unknown error"];

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON data"]);
    exit;
}

$conn->begin_transaction();

try {
    // Insert into order table
    $datetime = date("Y-m-d H:i:s");

    $orderStmt = $conn->prepare("INSERT INTO `order` (datetime) VALUES (?)");
    $orderStmt->bind_param("s", $datetime);
    $orderStmt->execute();
    $orderId = $orderStmt->insert_id;
    $orderStmt->close();

    foreach ($data as $itemName => $item) {
        $inventoryId = intval($item['inventoryId']);
        $quantity = intval($item['quantity']);
        $price = floatval($item['price']);

        if ($quantity <= 0) {
            continue;
        }

        // Fetch inventory details
        $query = $conn->prepare("SELECT genericName, brandName, milligram, dosageForm, `group`, quantity FROM inventory WHERE inventoryId = ?");
        $query->bind_param("i", $inventoryId);
        $query->execute();
        $query->bind_result($genericName, $brandName, $milligram, $dosageForm, $group, $currentStock);

        if ($query->fetch() === false) {
            throw new Exception("Failed to fetch inventory details for inventoryId: " . $inventoryId);
        }

        $query->close();

        if ($currentStock < $quantity) {
            throw new Exception("Not enough stock for $itemName");
        }

        // Calculate total price
        $total = $quantity * $price;

        // Insert into order_items table
        $itemStmt = $conn->prepare("
            INSERT INTO order_items (orderId, inventoryId, genericName, brandName, milligram, dosageForm, quantity, price, `group`, total) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $itemStmt->bind_param(
            "iissisidsd",
            $orderId,
            $inventoryId,
            $genericName,
            $brandName,
            $milligram,
            $dosageForm,
            $quantity,
            $price,
            $group,
            $total
        );
        $itemStmt->execute();
        $itemStmt->close();

        // Update stock
        $update = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE inventoryId = ?");
        $update->bind_param("ii", $quantity, $inventoryId);
        $update->execute();
        $update->close();
    }

    $conn->commit();
    $response = ["success" => true, "message" => "Order placed successfully"];
} catch (Exception $e) {
    $conn->rollback();
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);