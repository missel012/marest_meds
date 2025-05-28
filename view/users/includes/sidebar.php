<?php
// Ensure the session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include("../../dB/config.php");

// Fetch user data from the database using the email stored in the session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "SELECT firstName, lastName, profilePicture, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Update session variables with user data
    $_SESSION['firstName'] = $user['firstName'];
    $_SESSION['lastName'] = $user['lastName'];
    $_SESSION['profilePicture'] = $user['profilePicture'];
    $_SESSION['role'] = $user['role'];
} else {
    // Redirect to login if email is not set in the session
    header("Location: ../../login.php");
    exit();
}
?>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Profile Section -->
    <div class="profile-sidebar">
      <img src="<?= $_SESSION['profilePicture'] && file_exists($_SESSION['profilePicture']) ? htmlspecialchars($_SESSION['profilePicture']) : '../../assets/img/default-user.png' ?>" 
           alt="Profile Image" class="profile-img-sidebar">
      <div class="profile-info-sidebar">
        <h4><?= htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']) ?></h4>
        <span><?= htmlspecialchars($_SESSION['role'] === 'user' ? 'User' : ucfirst($_SESSION['role'])) ?></span>
      </div>
    </div>
    <!-- End Profile Section -->

    <li class="nav-item">
      <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : 'collapsed'; ?>" href="index.php">
        <i class="bi bi-grid"></i>
        <span>Home</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'active' : 'collapsed'; ?>" href="products.php">
        <i class="bi bi-box-seam"></i>
        <span>Products</span>
      </a>
    </li><!-- End Medicine Available Nav -->

    <li class="nav-item">
      <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'track_orders.php') ? 'active' : 'collapsed'; ?>" href="track_orders.php ">
        <i class="bi bi-basket3"></i>
        <span>Track Orders</span>
      </a>
    </li><!-- End Prescription Orders Nav -->


    <li class="nav-heading">Pages</li>

    <li class="nav-item">
      <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : 'collapsed'; ?>" href="profile.php ">
        <i class="bi bi-person"></i>
        <span>Profile</span>
      </a>
    </li><!-- End Profile Page Nav -->

    <li class="nav-item">
      <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="bi bi-box-arrow-right logout-icon"></i>
        <span>Logout</span>
      </a>
    </li><!-- End Login Page Nav -->

  </ul>
</aside><!-- End Sidebar-->

<main id="main" class="main">

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="../../logout.php" class="btn btn-primary">Logout</a>
            </div>
        </div>
    </div>
</div>

  <style>
  .profile-sidebar {
    display: flex;
    align-items: center;
    padding: 15px;
    background-color: #EDFFE9;
    border-bottom: 1px solid #dee2e6;
  }

  .profile-img-sidebar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
  }

  .profile-info-sidebar h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
  }

  .profile-info-sidebar span {
    font-size: 14px;
    color: #6c757d;
  }

  /* Disable active state for logout */
  .nav-link[data-bs-target="#logoutModal"] {
      color: black !important;
      background: none !important;
  }

  .nav-link[data-bs-target="#logoutModal"]:hover {
      color: #5AB94A !important;
      background: #E3F8E3 !important;
  }

  /* Change logout icon color */
  .logout-icon {
      color: #DB5C79 !important; /* Default color */
  }

  .nav-link[data-bs-target="#logoutModal"]:hover .logout-icon {
      color: #C04A67 !important; /* Hover color */
  }

  /* Modal styles */
  .modal-content {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .modal-header {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
  }

  .modal-title {
      font-weight: bold;
      color: #DB5C79;
  }

  .btn-primary {
      background-color: #6CCF54 !important; /* Logout button color */
      border-color: #6CCF54 !important;
  }

  .btn-primary:hover {
      background-color: #5AB94A !important;
      border-color: #5AB94A !important;
  }

  .btn-secondary {
      background-color: #DB5C79 !important; /* Cancel button color */
      border-color: #DB5C79 !important;
  }

  .btn-secondary:hover {
      background-color: #C04A67 !important;
      border-color: #C04A67 !important;
  }

  /* Ensure modal is above other elements */
  .modal-backdrop {
      z-index: 1040 !important;
  }

  .modal {
      z-index: 1050 !important;
  }
</style>

<script>
    function confirmLogout(event) {
        event.preventDefault();
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "../../logout.php"; // Redirect to logout handler
        }
    }
</script>