<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../dB/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventoryId = $_POST['inventoryId'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE inventory SET quantity=? WHERE inventoryId=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $quantity, $inventoryId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
