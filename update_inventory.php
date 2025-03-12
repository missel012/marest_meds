<?php
include("../../dB/config.php");

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventoryId = $_POST['inventoryId'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE inventory SET quantity = ? WHERE inventoryId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $quantity, $inventoryId);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Failed to update item.';
    }

    $stmt->close();
}

echo json_encode($response);
?>