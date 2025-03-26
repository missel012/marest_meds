  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <!-- Profile Section -->
      <div class="profile-sidebar">
        <img src="../../assets/img/user.jpg" alt="Profile Image" class="profile-img-sidebar">
        <div class="profile-info-sidebar">
          <h4>M. Datahan</h4>
          <span>Staff</span>
        </div>
      </div>
      <!-- End Profile Section -->

      <li class="nav-item">
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : 'collapsed'; ?>" href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'inventory.php') ? 'active' : 'collapsed'; ?>" href="inventory.php">
          <i class="bi bi-box-seam"></i>
          <span>Inventory</span>
        </a>
      </li><!-- End Medicine Available Nav -->

      <li class="nav-item">
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'prescriptionOrders.php') ? 'active' : 'collapsed'; ?>" href="prescriptionOrders.php ">
          <i class="bi bi-basket3"></i>
          <span>Prescription Orders</span>
        </a>
      </li><!-- End Prescription Orders Nav -->


      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'users-profile.php') ? 'active' : 'collapsed'; ?>" href="users-profile.php ">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == '../../login.php') ? 'active' : 'collapsed'; ?>" href="../../login.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Login Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

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
</style>