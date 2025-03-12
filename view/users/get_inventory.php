<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../dB/config.php");

if (isset($_GET['id'])) {
    $inventoryId = $_GET['id'];

    $query = "SELECT * FROM inventory WHERE inventoryId=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $inventoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        echo json_encode($item);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
    }

    $stmt->close();
    $conn->close();
}
?>
