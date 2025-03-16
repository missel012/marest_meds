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
    foreach ($data as $itemName => $item) {
        $inventoryId = intval($item['inventoryId']);
        $quantity = intval($item['quantity']);
        $price = floatval($item['price']);
        $datetime = date("Y-m-d H:i:s");

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

        error_log("Fetched details - Medicine Group: " . var_export($group, true));
        $query->close();



        if ($currentStock < $quantity) {
            throw new Exception("Not enough stock for $itemName");
        }

        // Calculate total price
        $total = $quantity * $price;

        // Insert into orders table with all fields
        $stmt = $conn->prepare("
            INSERT INTO `order` (inventoryId, genericName, brandName, milligram, dosageForm, quantity, price, `group`, total, datetime) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issisidsid",
            $inventoryId,
            $genericName,
            $brandName,
            $milligram,
            $dosageForm,
            $quantity,
            $price,
            $group,
            $total,
            $datetime
        );
        $stmt->execute();
        $stmt->close();

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
