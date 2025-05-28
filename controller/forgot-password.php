<?php
// PHPMailer password reset handler
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Only require PHPMailer if it exists (prevents fatal error if not installed)
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
} else {
    session_start();
    $_SESSION['message'] = "PHPMailer is not installed. Please run <code>composer require phpmailer/phpmailer</code> in your project root.";
    header("Location: ../forgot-password.php");
    exit();
}

include("../db/config.php");
session_start();

if (isset($_POST['send_reset'])) {
    $email = trim($_POST['email']);
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Check if reset_token columns exist, if not, show error
        $columnsRes = $conn->query("SHOW COLUMNS FROM users LIKE 'reset_token'");
        if ($columnsRes->num_rows == 0) {
            $_SESSION['message'] = "Database error: Please add <b>reset_token</b> and <b>reset_expires</b> columns to your <b>users</b> table:<br>
                <code>ALTER TABLE users ADD reset_token VARCHAR(255) NULL, ADD reset_expires DATETIME NULL;</code>";
            header("Location: ../forgot-password.php");
            exit();
        }

        // Generate token and expiry
        $token = bin2hex(random_bytes(32));
        // Set expiry to 24 hours from now
        $expires = date("Y-m-d H:i:s", strtotime("+24 hours"));

        // Store token in DB
        $update = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
        $update->bind_param("sss", $token, $expires, $email);
        $update->execute();


        // Send email
        $mail = new PHPMailer(true);
        try {
            // SMTP config
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'datahan.marisol012@gmail.com';
            $mail->Password = 'brzz efgx qmmy tzyf';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('datahan.marisol012@gmail.com', 'Marest Meds');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $resetLink = "http://localhost/IT322/reset-password.php?token=$token";
            $mail->Body = "Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a><br>This link will expire in 1 hour.";

            $mail->send();
            $_SESSION['message'] = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $_SESSION['message'] = "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['message'] = "Email address not found.";
    }
    header("Location: ../forgot-password.php");
    exit();
}
?>