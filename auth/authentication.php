<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Call this function to log out a user
function logout() {
    session_unset();
    session_destroy();
}

// Returns true if user is authenticated
function isAuthenticated() {
    return isset($_SESSION['email']);
}

// Helper for debugging: check if headers already sent
function checkHeaders() {
    if (headers_sent($file, $line)) {
        echo "<!-- WARNING: Headers already sent in $file on line $line. SweetAlert may not work. -->";
    }
}

// Call this at the VERY TOP of pages that require authentication, before any HTML or whitespace!
function requireAuth() {
    checkHeaders();
    if (headers_sent($file, $line)) {
        // Output a clear error and stop execution
        echo "<b>ERROR:</b> Output started at $file on line $line. Call requireAuth()/requireRole() before any output or includes that produce output.";
        exit();
    }
    if (!isAuthenticated()) {
        header('Location: /IT322/auth/unauthorized.php?msg=Requires+Login&type=warning&redirect=/IT322/login.php');
        exit();
    }
}

// Call this function to log in a user with a role
function login($email, $role = null) {
    $_SESSION['email'] = $email;
    if ($role !== null) {
        $_SESSION['role'] = $role;
    }
}

// Returns the current user's role
function getUserRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

// Require a specific role, show SweetAlert if unauthorized
function requireRole($role) {
    requireAuth();
    if (headers_sent($file, $line)) {
        echo "<b>ERROR:</b> Output started at $file on line $line. Call requireAuth()/requireRole() before any output or includes that produce output.";
        exit();
    }
    if (getUserRole() !== $role) {
        checkHeaders();
        // Redirect to the correct dashboard based on the user's actual role
        $actualRole = getUserRole();
        $redirect = '/IT322/index.php';
        if ($actualRole === 'admin') {
            $redirect = '/IT322/view/admin/index.php';
        } elseif ($actualRole === 'staff') {
            $redirect = '/IT322/view/staff/index.php';
        } elseif ($actualRole === 'user') {
            $redirect = '/IT322/view/users/index.php';
        }
        header('Location: /IT322/auth/unauthorized.php?msg=Unauthorized&type=error&redirect=' . urlencode($redirect));
        exit();
    }
}

// IMPORTANT: Call requireAuth() or requireRole() BEFORE any HTML or output in your PHP pages!
?>
