<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">


<ul class="sidebar-nav" id="sidebar-nav">

 <!-- Profile Section -->
 <div class="profile-sidebar">
    <img src="../../assets/img/profile-img.jpg" alt="Profile Image" class="profile-img-sidebar">
    <div class="profile-info-sidebar">
      <h4>Esther</h4>
      <span>Super Admin</span>
    </div>
  </div>
  <!-- End Profile Section -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="index.php">
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
    <a class="nav-link collapsed" href="users-profile.php">
      <i class="bi bi-person"></i>
      <span>Profile</span>
    </a>
  </li><!-- End Profile Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="pages-about.php">
      <i class="bi bi-question-circle"></i>
      <span>About us</span>
    </a>
  </li><!-- End F.A.Q Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="pages-contact.php">
      <i class="bi bi-envelope"></i>
      <span>Contact</span>
    </a>
  </li><!-- End Contact Page Nav -->

  <li class="nav-item">
    <a class="nav-link collapsed" href="pages-login.html">
      <i class="bi bi-box-arrow-in-right"></i>
      <span>Logout</span>
    </a>
  </li><!-- End Login Page Nav -->



</aside><!-- End Sidebar-->

<main id="main" class="main">

<style>
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
  }

  .profile-info-sidebar span {
    font-size: 14px;
    color: #6c757d;
  }
</style>