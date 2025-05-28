<?php
session_start();
include("../db/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Update password
        $update = "UPDATE users SET password='$new_password' WHERE email='$email'";
        if (mysqli_query($conn, $update)) {
            $_SESSION['message'] = "Password successfully updated! You can now login.";
        } else {
            $_SESSION['message'] = "Error updating password. Please try again.";
        }
    } else {
        $_SESSION['message'] = "Email address not found.";
    }
    header("Location: ../forgot-password.php");
    exit();
} else {
    header("Location: ../forgot-password.php");
    exit();
}
?>