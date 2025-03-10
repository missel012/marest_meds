  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

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
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : 'collapsed'; ?>" href="profile.php ">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : 'collapsed'; ?>" href="about.php ">
          <i class="bi bi-question-circle"></i>
          <span>About Us</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item">
      <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : 'collapsed'; ?>" href="contact.php ">
          <i class="bi bi-envelope"></i>
          <span>Contact</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-item">
      <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'logout.php') ? 'active' : 'collapsed'; ?>" href="logout.php ">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Login Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">