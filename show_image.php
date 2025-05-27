<?php
// show_image.php

$inventoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$conn = new mysqli("localhost", "root", "", "datahan_eblacas"); // Update database name
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT image_blob FROM inventory WHERE inventoryId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inventoryId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $imageData = $row['image_blob'];

    // Detect MIME type using finfo
    $finfo = finfo_open();
    $mimeType = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
    finfo_close($finfo);

    header("Content-Type: $mimeType");
    echo $imageData;
} else {
    // fallback image
    header("Content-Type: image/png");
    readfile("assets/img/not-found.png");
}

$stmt->close();
$conn->close();
?>
