<?php
include("../../dB/config.php");

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    // Sanitize
    $querySafe = mysqli_real_escape_string($conn, $query);

    // Check in Inventory
    $invSql = "SELECT * FROM inventory WHERE genericName LIKE '%$querySafe%' OR brandName LIKE '%$querySafe%'";
    $invResult = mysqli_query($conn, $invSql);

    if (mysqli_num_rows($invResult) > 0) {
        header("Location: inventory.php?search=" . urlencode($query));
        exit();
    }

    // Check in Staff
    $staffSql = "SELECT * FROM staff WHERE staff_name LIKE '%$querySafe%' OR email LIKE '%$querySafe%'";
    $staffResult = mysqli_query($conn, $staffSql);

    if (mysqli_num_rows($staffResult) > 0) {
        header("Location: staff.php?search=" . urlencode($query));
        exit();
    }

    // No matches
    header("Location: no-results.php?query=" . urlencode($query));
    exit();
}
?>
