<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include("../../dB/config.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $inventoryId = intval($_GET['id']);

    $query = "SELECT * FROM inventory WHERE inventoryId=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $inventoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        // Encode image as base64 if present
        if (!empty($item['image'])) {
            $item['image'] = base64_encode($item['image']);
        } else {
            $item['image'] = null;
        }
        echo json_encode($item);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
}
?>
