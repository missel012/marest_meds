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
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="index.php" class="logo d-flex align-items-center">
<img src="../../assets/img/marest_logo.png" alt="Logo">

<span class="d-none d-lg-block">
    <span style="color:  #db5c79 ; font-weight: bold;">Marest</span>
    <span style="color:rgb(109, 204, 85); font-weight: bold;">Meds</span>
  </span>
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<div class="search-bar">
  <form class="search-form d-flex align-items-center" method="POST" action="#">
    <input type="text" name="query" placeholder="Search" title="Enter search keyword">
    <button type="submit" title="Search"><i class="bi bi-search"></i></button>
  </form>
</div><!-- End Search Bar --> 

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

    <li class="nav-item d-block d-lg-none">
      <a class="nav-link nav-icon search-bar-toggle " href="#">
        <i class="bi bi-search"></i>
      </a>
    </li><!-- End Search Icon-->


    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="<?= $_SESSION['profilePicture'] && file_exists($_SESSION['profilePicture']) ? htmlspecialchars($_SESSION['profilePicture']) : '../../assets/img/default-user.png' ?>" 
             alt="Profile" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2"><?= htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']) ?></span>
      </a><!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6><?= htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']) ?></h6>
          <span><?= htmlspecialchars($_SESSION['role'] === 'user' ? 'Staff' : ucfirst($_SESSION['role'])) ?></span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
            <i class="bi bi-person"></i>
            <span>My Profile</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a href="#" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
          </a>
        </li>
      </ul><!-- End Profile Dropdown Items -->

    </li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->

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