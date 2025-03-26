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
         alt="Profile Image" 
         class="profile-img-sidebar">
    <div class="profile-info-sidebar">
      <h4><?= htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']) ?></h4>
      <span><?= htmlspecialchars(ucfirst($_SESSION['role'])) ?></span>
    </div>
  </div>
  <!-- End Profile Section -->

  <li class="nav-item">
  <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : 'collapsed'; ?>" href="index.php">
      <i class="bi bi-grid"></i>
      <span>Dashboard</span>
    </a>
  </li><!-- End Dashboard Nav -->

    <li class="nav-item">
    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active' : 'collapsed'; ?>" href="inventory.php">
        <i class="bi bi-person"></i>
        <span>Inventory</span>
      </a>
    </li><!-- End Inventory Page Nav -->


    <li class="nav-item">
    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'prescription_orders.php' ? 'active' : 'collapsed'; ?>" href="prescription_orders.php">
        <i class="bi bi-person"></i>
        <span>Prescription Orders</span>
      </a>
    </li><!-- End Prescription Orders Page Nav -->

 
    <li class="nav-item">
    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'staff.php' ? 'active' : 'collapsed'; ?>" href="staff.php">
        <i class="bi bi-person"></i>
        <span>Staff</span>
      </a>
    </li><!-- End Prescription Orders Page Nav -->

  </li><!-- End Icons Nav -->

  <li class="nav-heading">Pages</li>

  <li class="nav-item">
  <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'users-profile.php' ? 'active' : 'collapsed'; ?>" href="users-profile.php">
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

.sidebar {
  position: fixed;
  top: 60px;
  left: 0;
  bottom: 0;
  width: 300px;
  z-index: 996;
  transition: all 0.3s;
  padding: 20px;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: #aab7cf transparent;
  box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1);
  background-color: #fff;
}

@media (max-width: 1199px) {
  .sidebar {
    left: -300px;
  }
}

.sidebar::-webkit-scrollbar {
  width: 5px;
  height: 8px;
  background-color: #fff;
}

.sidebar::-webkit-scrollbar-thumb {
  background-color: #aab7cf;
}

@media (min-width: 1200px) {

  #main,
  #footer {
    margin-left: 300px;
  }
}

@media (max-width: 1199px) {
  .toggle-sidebar .sidebar {
    left: 0;
  }
}

@media (min-width: 1200px) {

  .toggle-sidebar #main,
  .toggle-sidebar #footer {
    margin-left: 0;
  }

  .toggle-sidebar .sidebar {
    left: -300px;
  }
}

.sidebar-nav {
  padding: 0;
  margin: 0;
  list-style: none;
}

.sidebar-nav li {
  padding: 0;
  margin: 0;
  list-style: none;
}

.sidebar-nav .nav-item {
  margin-bottom: 5px;
}

.sidebar-nav .nav-heading {
  font-size: 11px;
  text-transform: uppercase;
  color: #899bbd;
  font-weight: 600;
  margin: 10px 0 5px 15px;
}

.sidebar-nav .nav-link {
  display: flex;
  align-items: center;
  font-size: 15px;
  font-weight: 600;
  color: #6CCF54;
  transition: 0.3;
  background: #EDFFE9;
  padding: 10px 15px;
  border-radius: 4px;
}

.sidebar-nav .nav-link i {
  font-size: 16px;
  margin-right: 10px;
  color: #6CCF54;
}

.sidebar-nav .nav-link.collapsed {
  color: black;
  background: #fff;
}

.sidebar-nav .nav-link.collapsed i {
  color: #899bbd;
}

.sidebar-nav .nav-link:hover {
  color: #6CCF54;
  background: #EDFFE9;
}

.sidebar-nav .nav-link:hover i {
  color: #6CCF54;
}

.sidebar-nav .nav-link .bi-chevron-down {
  margin-right: 0;
  transition: transform 0.2s ease-in-out;
}

.sidebar-nav .nav-link:not(.collapsed) .bi-chevron-down {
  transform: rotate(180deg);
}

.sidebar-nav .nav-content {
  padding: 5px 0 0 0;
  margin: 0;
  list-style: none;
}

.sidebar-nav .nav-content a {
  display: flex;
  align-items: center;
  font-size: 14px;
  font-weight: 600;
  color: #012970;
  transition: 0.3;
  padding: 10px 0 10px 40px;
  transition: 0.3s;
}

.sidebar-nav .nav-content a i {
  font-size: 6px;
  margin-right: 8px;
  line-height: 0;
  border-radius: 50%;
}

.sidebar-nav .nav-content a:hover,
.sidebar-nav .nav-content a.active {
  color: #4154f1;
}

.sidebar-nav .nav-content a.active i {
  background-color: #4154f1;
}
  .profile-sidebar {
    display: flex;
    align-items: center;
    padding: 15px;
    background-color: #f8f9fa;
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
    color: black;
  }

  .profile-info-sidebar span {
    font-size: 14px;
    color: #6c757d;
  }

  /* Fix hover effect for logout link */
  .nav-link {
      color: #6CCF54;
      background: #EDFFE9;
      transition: background-color 0.3s, color 0.3s;
  }

  .nav-link:hover {
      color: #5AB94A;
      background: #E3F8E3;
  }

  .nav-link.collapsed {
      color: black;
      background: #fff;
  }

  .nav-link.collapsed:hover {
      color: #6CCF54;
      background: #EDFFE9;
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