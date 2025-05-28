<?php
header('Content-Type: application/json');
include("../../dB/config.php");
include("../../auth/authentication.php"); // Include authentication file to access session data

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

$cart = $data['cart'];
$address = $data['address'];
$phoneNumber = $data['phoneNumber'];
$orderDateTime = $data['orderDateTime'];
$estimatedDelivery = $data['estimatedDelivery'];

$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
if (!$email) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

// Fetch userId using the email
$stmtFetchUserId = $conn->prepare("SELECT userId FROM users WHERE email = ?");
$stmtFetchUserId->bind_param("s", $email);
$stmtFetchUserId->execute();
$stmtFetchUserId->bind_result($userId);
$stmtFetchUserId->fetch();
$stmtFetchUserId->close();

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Invalid email. User does not exist.']);
    exit;
}

$conn->begin_transaction();

try {
    // Insert into `order` table
    $stmtOrder = $conn->prepare("INSERT INTO `order` (userId, datetime) VALUES (?, ?)");
    $stmtOrder->bind_param("is", $userId, $orderDateTime);
    $stmtOrder->execute();
    $orderId = $conn->insert_id;

    // Insert into `track_order` table
    $stmtTrackOrder = $conn->prepare("INSERT INTO track_order (orderId, userId, status, orderDateTime, estimatedDelivery) VALUES (?, ?, 'ordered', ?, ?)");
    $stmtTrackOrder->bind_param("iiss", $orderId, $userId, $orderDateTime, $estimatedDelivery);
    $stmtTrackOrder->execute();
    $trackId = $conn->insert_id;

    // Insert into `order_items` and `track_order_details`, and update inventory
    $stmtOrderItems = $conn->prepare("INSERT INTO order_items (orderId, inventoryId, genericName, brandName, milligram, dosageForm, quantity, price, `group`, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
    $stmtTrackOrderDetails = $conn->prepare("INSERT INTO track_order_details (trackId, inventoryId, quantity, price) VALUES (?, ?, ?, ?)");
    $stmtUpdateInventory = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE inventoryId = ?");

    foreach ($cart as $item) {
        $totalPrice = $item['quantity'] * $item['price'];
    
        // Insert into `order_items`
        $stmtOrderItems->bind_param(
            "iissisiids",
            $orderId,
            $item['inventoryId'],
            $item['genericName'],
            $item['brandName'],
            $item['milligram'],
            $item['dosageForm'],
            $item['quantity'],
            $item['price'],
            $item['group'], // Correctly pass the `group` field
            $totalPrice
        );
        $stmtOrderItems->execute();
    
        // Insert into `track_order_details`
        $stmtTrackOrderDetails->bind_param("iiid", $trackId, $item['inventoryId'], $item['quantity'], $item['price']);
        $stmtTrackOrderDetails->execute();
    
        // Update inventory quantity
        $stmtUpdateInventory->bind_param("ii", $item['quantity'], $item['inventoryId']);
        $stmtUpdateInventory->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Order confirmed successfully.']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Failed to confirm order: ' . $e->getMessage()]);
}
?>