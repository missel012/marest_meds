<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Marest Meds</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
            .forgot-password-container {
        width: 350px;
        margin: 40px auto;
        padding: 24px 24px 18px 24px;
        background: #fff;
        border-radius: 35px; /* Super rounded */
        box-shadow: 0 4px 16px rgba(179,2,2,0.10), 0 6px 20px 0 rgba(0,0,0,0.08);
        text-align: center;
        }
                .forgot-password-container form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        }
      .forgot-password-container input[type="email"],
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
      .forgot-password-container .alert {
        margin-bottom: 16px;
        color: #b30202;
        background: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 8px;
        padding: 8px;
      }
      .forgot-password-container a {
        display: block;
        margin-top: 10px;
        color: #DB5C79;
        text-decoration: underline;
      }
    </style>
</head>
<body>
    <body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8faff;">
    <div class="forgot-password-container">
        <h2>Reset Password</h2>
        <?php
        session_start();
        if (isset($_SESSION['message'])) {
            echo '<div class="alert">'.$_SESSION['message'].'</div>';
            unset($_SESSION['message']);
        }
        ?>
        <form action="controller/forgot-password.php" method="POST">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" required>
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>
            <button type="submit">Reset Password</button>
        </form>
        <a href="login.php">Back to Login</a>
    </div>
</body>
</html>