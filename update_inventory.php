<?php
include("../../dB/config.php");

header('Content-Type: application/json');
$response = ['success' => false];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $inventoryId = $_POST['inventoryId'];
        $genericName = $_POST['genericName'];
        $brandName = $_POST['brandName'];
        $milligram = $_POST['milligram'];
        $dosageForm = $_POST['dosageForm'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $group = $_POST['group'];

        $query = "UPDATE inventory SET 
                    genericName = ?, 
                    brandName = ?, 
                    milligram = ?, 
                    dosageForm = ?, 
                    quantity = ?, 
                    price = ?, 
                    `group` = ? 
                  WHERE inventoryId = ?";
        
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param("ssdsdssi", $genericName, $brandName, $milligram, $dosageForm, $quantity, $price, $group, $inventoryId);

        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }

        $response['success'] = true;
        $stmt->close();
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
