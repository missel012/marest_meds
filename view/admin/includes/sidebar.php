<?php
include("../../dB/config.php"); // Ensure this file contains your database connection

// Fetch user details for the sidebar
$userId = 1; // Replace with dynamic user ID if needed
$query = "SELECT firstName, lastName, profilePicture, role FROM users WHERE userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <!-- Profile Section -->
  <div class="profile-sidebar">
    <img src="<?= $user['profilePicture'] && file_exists($user['profilePicture']) ? htmlspecialchars($user['profilePicture']) : './assets/images/user-icon.png' ?>" 
         alt="Profile Image" 
         class="profile-img-sidebar">
    <div class="profile-info-sidebar">
      <h4 style="color: black;"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></h4>
      <span><?= $user['role'] === 'admin' ? 'Admin' : 'Staff' ?></span>
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
  <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pages-about.php' ? 'active' : 'collapsed'; ?>" href="pages-about.php">
      <i class="bi bi-question-circle"></i>
      <span>About us</span>
    </a>
  </li><!-- End F.A.Q Page Nav -->

  <li class="nav-item">
  <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pages-contact.php' ? 'active' : 'collapsed'; ?>" href="pages-contact.php">
      <i class="bi bi-envelope"></i>
      <span>Contact</span>
    </a>
  </li><!-- End Contact Page Nav -->

  <li class="nav-item">
  <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : 'collapsed'; ?>" href="../../login.php">
      <i class="bi bi-box-arrow-in-right"></i>
      <span>Logout</span>
    </a>
  </li><!-- End Login Page Nav -->



</aside><!-- End Sidebar-->

<main id="main" class="main">

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
</style>