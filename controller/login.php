<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../db/config.php"); // Include database configuration
require_once __DIR__ . '/../auth/authentication.php'; // Use login() helper

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Store the email in the session to retain it after errors
    $_SESSION['login_email'] = $email;

    // Fetch user details from the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);


        // Verify the password (hashed or plain-text)
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            // Use login() helper to set session variables
            login($user['email'], $user['role']);

            // Clear the stored email after successful login
            unset($_SESSION['login_email']);

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: ../view/admin/index.php");
            } elseif ($user['role'] === 'user') {
                header("Location: ../view/users/index.php");
            } elseif ($user['role'] === 'staff') {
                header("Location: ../view/staff/index.php");
            } elseif ($user['role'] === 'customer') {
                header("Location: ../view/customer/index.php");
            } else {
                $_SESSION['message'] = "Unknown role.";
                $_SESSION['code'] = "error";
                header("Location: ../login.php");
            }
            exit();
        } else {
            $_SESSION['message'] = "Invalid email or password.";
            $_SESSION['code'] = "error";
        }
    } else {
        $_SESSION['message'] = "Invalid email or password.";
        $_SESSION['code'] = "error";
    }

    header("Location: ../login.php");
    exit();
}
?>
