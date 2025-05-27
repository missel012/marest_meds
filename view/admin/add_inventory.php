<?php
include("../../dB/config.php");

// ...existing code to get other fields...
$genericName = $_POST['genericName'];
$brandName = $_POST['brandName'];
$milligram = $_POST['milligram'];
$dosageForm = $_POST['dosageForm'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$group = $_POST['group'];

// Handle image upload as BLOB
$imageData = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageData = file_get_contents($_FILES['image']['tmp_name']);
} else {
    echo json_encode(['success' => false, 'error' => "Can't add without image"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO inventory (genericName, brandName, milligram, dosageForm, quantity, price, `group`, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssidsb", $genericName, $brandName, $milligram, $dosageForm, $quantity, $price, $group, $imageData);
$stmt->send_long_data(7, $imageData);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    // Log error details to a file for debugging
    $logMsg = "MySQL Error: " . $stmt->error . "\n";
    $logMsg .= "Query: INSERT INTO inventory (genericName, brandName, milligram, dosageForm, quantity, price, `group`, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)\n";
    $logMsg .= "Params: " . json_encode([
        $genericName, $brandName, $milligram, $dosageForm, $quantity, $price, $group,
        ($imageData !== null ? 'BINARY DATA' : 'NULL')
    ]) . "\n";
    file_put_contents(__DIR__ . '/inventory_add_error.log', $logMsg, FILE_APPEND);

    echo json_encode(['success' => false, 'error' => $stmt->error, 'log' => $logMsg]);
}
$stmt->close();
$conn->close();
?>
