<?php
include("../dB/config.php");
session_start();

if (isset($_POST['registration'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $phoneNumber = $_POST['phoneNumber'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'user';

    // Validate if confirm password and password match
    if ($password != $cpassword) {
        $_SESSION['message'] = "Password and Confirm Password do not match";
        $_SESSION['code'] = "error";
        header("location:../registration.php");
        exit(0);
    }

    // Validate if email already exists
    $query = "SELECT `email` FROM `users` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['message'] = "Email already exists";
        $_SESSION['code'] = "error";
        header("location:../registration.php");
        exit(0);
    }

    // Insert user data into the database
    $insert_query = "INSERT INTO users (firstName, lastName, email, password, phoneNumber, gender, birthday, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssssss", $firstName, $lastName, $email, $password, $phoneNumber, $gender, $birthday, $role);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registered Successfully";
        $_SESSION['code'] = "success";
        header("location:../login.php");
        exit(0);
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>