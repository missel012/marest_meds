<?php
include("../../dB/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventoryId = $_POST['inventoryId'];

    $stmt = $conn->prepare("DELETE FROM inventory WHERE inventoryId = ?");
    $stmt->bind_param("i", $inventoryId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
