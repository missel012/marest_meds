<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../dB/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $genericName = $_POST['genericName'];
    $brandName = $_POST['brandName'];
    $milligram = $_POST['milligram'];
    $dosageForm = $_POST['dosageForm'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $group = $_POST['group'];

    $query = "INSERT INTO inventory (genericName, brandName, milligram, dosageForm, quantity, price, `group`) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssids", $genericName, $brandName, $milligram, $dosageForm, $quantity, $price, $group);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
