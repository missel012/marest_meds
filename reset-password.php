<?php
include("db/config.php");
session_start();

// Set PHP timezone to match your MySQL timezone (Asia/Manila)
date_default_timezone_set('Asia/Manila');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // DEBUG: Output to browser for easier debugging (remove after!)
    /*
    $dbg = $conn->query("SELECT email, reset_token, reset_expires, NOW() as now FROM users WHERE reset_token='$token'");
    $dbgRow = $dbg->fetch_assoc();
    echo "<pre style='background:#fffbe6;border:1px solid #db5c79;padding:10px;'>";
    echo "DEBUG INFO:<br>";
    echo "Token from URL: [$token]<br>";
    echo "DB Row: "; print_r($dbgRow);
    echo "</pre>";
    */

    // Check for empty token or whitespace
    if (empty($token) || trim($token) === '') {
        // echo "<b style='color:red'>Token is empty or whitespace</b>";
        $_SESSION['message'] = "Invalid or expired reset link.";
        exit();
    }

    // DEBUG: Show time difference
    /*
    if ($dbgRow && isset($dbgRow['reset_expires'], $dbgRow['now'])) {
        $expires = strtotime($dbgRow['reset_expires']);
        $now = strtotime($dbgRow['now']);
        echo "<pre style='background:#fffbe6;border:1px solid #db5c79;padding:10px;'>";
        echo "reset_expires: {$dbgRow['reset_expires']}<br>";
        echo "now: {$dbgRow['now']}<br>";
        echo "Difference (seconds): " . ($expires - $now) . "<br>";
        echo "If negative, the link is expired.<br>";
        echo "</pre>";
    }
    */

    // Fix: Use PHP time for expiry check instead of MySQL NOW()
    $query = "SELECT * FROM users WHERE reset_token=? AND reset_token IS NOT NULL AND reset_token != ''";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        // echo "<b style='color:red'>No matching user found for token</b>";
        $_SESSION['message'] = "Invalid or expired reset link.";
        exit();
    }

    $user = $result->fetch_assoc();
    // Now check expiry using PHP time (to avoid timezone mismatch)
    if (strtotime($user['reset_expires']) < time()) {
        // echo "<b style='color:red'>Token expired (PHP time check)</b>";
        $_SESSION['message'] = "Invalid or expired reset link.";
        exit();
    }

    // Add error message variable for password mismatch
    $password_error = "";
    $show_success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'], $_POST['confirm_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            $password_error = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE email=?");
            $update->bind_param("ss", $hashed_password, $user['email']);
            $update->execute();
            $_SESSION['message'] = "Password reset successful. You can now login.";
            $show_success = true;
            // Do not redirect immediately, let JS handle it
        }
    }
} else {
    $_SESSION['message'] = "Invalid reset link.";
    header("Location: forgot-password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Marest Meds</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .forgot-password-container {
            width: 350px;
            margin: 40px auto;
            padding: 24px 24px 18px 24px;
            background: #fff;
            border-radius: 35px;
            box-shadow: 0 4px 16px rgba(179,2,2,0.10), 0 6px 20px 0 rgba(0,0,0,0.08);
            text-align: center;
        }
        .forgot-password-container form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .forgot-password-container input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 12px 0 18px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .forgot-password-container button {
            background: #DB5C79;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        .error-message {
            color: #db5c79;
            margin-bottom: 10px;
            width: 100%;
            text-align: left;
        }
        .swal2-title.swal2-title-sm {
            font-size: 1rem !important;
        }
    </style>
</head>
<body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8faff;">
    <div class="forgot-password-container">
        <h2>Set New Password</h2>
        <!-- Remove inline error message, use SweetAlert instead -->
        <form method="POST" id="resetForm">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <button type="submit">Set Password</button>
        </form>
    </div>
    <script>
        // Show SweetAlert for password mismatch
        <?php if (!empty($password_error)): ?>
        Swal.fire({
            position: 'top-end',
            toast: true,
            width: '22em',
            icon: 'error',
            title: '<?php echo $password_error; ?>',
            showConfirmButton: false,
            timer: 2500,
            customClass: {
                title: 'swal2-title-sm'
            }
        });
        <?php endif; ?>

        // Show SweetAlert for success and redirect
        <?php if (!empty($show_success)): ?>
        Swal.fire({
            position: 'top-end',
            toast: true,
            width: '22em',
            icon: 'success',
            title: 'Password reset successful. You can now login.',
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                title: 'swal2-title-sm'
            }
        }).then(() => {
            window.location.href = 'login.php';
        });
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 2200);
        <?php endif; ?>
    </script>
</body>
</html>
