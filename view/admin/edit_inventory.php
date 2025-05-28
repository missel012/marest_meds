<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../dB/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventoryId = $_POST['inventoryId'];
    $genericName = $_POST['genericName'];
    $brandName = $_POST['brandName'];
    $milligram = $_POST['milligram'];
    $dosageForm = $_POST['dosageForm'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $group = $_POST['group'];

    // Handle image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $query = "UPDATE inventory SET genericName=?, brandName=?, milligram=?, dosageForm=?, quantity=?, price=?, `group`=?, image=? WHERE inventoryId=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssidsbi", $genericName, $brandName, $milligram, $dosageForm, $quantity, $price, $group, $imageData, $inventoryId);
    } else {
        $query = "UPDATE inventory SET genericName=?, brandName=?, milligram=?, dosageForm=?, quantity=?, price=?, `group`=? WHERE inventoryId=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssidsi", $genericName, $brandName, $milligram, $dosageForm, $quantity, $price, $group, $inventoryId);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
