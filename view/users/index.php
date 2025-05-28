<?php
require_once __DIR__ . '/../../auth/authentication.php';
requireRole('user');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php");

$firstName = 'Guest'; // default fallback name

if (isset($_SESSION['user_id'])) {
    $userId = intval($_SESSION['user_id']);
    $stmt = $conn->prepare("SELECT firstName, lastName FROM users WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($dbFirstName, $dbLastName);
    if ($stmt->fetch()) {
        $_SESSION['firstName'] = $dbFirstName;
        $_SESSION['lastName'] = $dbLastName;
    }
    $stmt->close();
}


?>
<div class="welcome-banner position-relative text-white mb-4" style="background: url('assets/img/pharmacy-bg.jpg') center center / cover no-repeat; height: 250px; border-radius: 15px;">
  <div class="overlay position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0,0,0,0.5); border-radius: 15px;"></div>
  <div class="container h-100 d-flex flex-column justify-content-center align-items-start position-relative p-4">
    <h2 class="fw-light mb-2">Hi, <?= htmlspecialchars($_SESSION['firstName']) ?>!</h2>
    <h1 class="display-5 fw-bold">
      Welcome to <span style="color: #db5c79;">Marest</span> <span style="color: #6ccf54;">Meds</span>
    </h1>
  </div>
</div>


<!-- Dashboard Buttons as Cards -->
<div class="d-flex justify-content-center gap-4 flex-wrap mb-5">
    <!-- What's New Button -->
    <div class="card card-button shadow-sm text-center" data-bs-toggle="modal" data-bs-target="#whatsNewModal">
        <div class="card-body">
            <i class="bi bi-stars display-4 text-primary mb-2"></i>
            <h5 class="card-title">What's New</h5>
        </div>
    </div>

    <!-- Prescription Required Button -->
    <div class="card card-button shadow-sm text-center" data-bs-toggle="modal" data-bs-target="#prescriptionModal">
        <div class="card-body">
            <i class="bi bi-prescription display-4 text-danger mb-2"></i>
            <h5 class="card-title">Prescription</h5>
        </div>
    </div>

    <!-- Stocks Button -->
    <a href="inventory.php" class="text-decoration-none">
        <div class="card card-button shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-box-seam display-4 text-success mb-2"></i>
                <h5 class="card-title">Stocks</h5>
            </div>
        </div>
    </a>

    <!-- Place Order Button -->
    <a href="orders_prescription.php" class="text-decoration-none">
        <div class="card card-button shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-cart-plus display-4 text-warning mb-2"></i>
                <h5 class="card-title">Order</h5>
            </div>
        </div>
    </a>
</div>


<!-- MODALS -->
<!-- What's New Modal -->
<div class="modal fade" id="whatsNewModal" tabindex="-1" aria-labelledby="whatsNewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🆕 What's New</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">
        <ul class="list-group list-group-flush">
          <?php
          $newMedsQuery = "SELECT brandName FROM inventory ORDER BY inventoryId DESC LIMIT 5";
          $newMeds = mysqli_query($conn, $newMedsQuery);
          while ($med = mysqli_fetch_assoc($newMeds)) :
          ?>
            <li class="list-group-item d-flex align-items-center">
              <i class="bi bi-capsule-pill me-2 text-primary fs-5"></i>
              <span class="fw-semibold"><?= htmlspecialchars($med['brandName']) ?></span>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>
</div>


<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1" aria-labelledby="prescriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🩺 Prescription Required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body row">
        <?php
        $prescriptionQuery = "SELECT brandName AS name, image AS image_path FROM inventory WHERE `group` = 'prescription' LIMIT 4";
        $prescriptionMeds = mysqli_query($conn, $prescriptionQuery);
        while ($med = mysqli_fetch_assoc($prescriptionMeds)) :
        ?>
        <div class="col-md-3 mb-3">
            <div class="card border-danger">
                <img src="<?= htmlspecialchars($med['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($med['name']) ?>" style="height: 150px; object-fit: cover;">
                <div class="card-body">
                    <h6 class="card-title text-center text-danger"><?= htmlspecialchars($med['name']) ?></h6>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/footer.php"); ?>

<!-- Bootstrap Icons (add this if not already included) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .pagetitle h1 {
        font-weight: 700;
        color: #2a8c7c;
    }
    .card-button {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    cursor: pointer;
    transition: transform 0.2s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    }

    .card-button:hover {
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    }

    .card-button .card-body {
        margin-top: 2rem;
        padding: 0;
    }

    .card-button .card-title {
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.2rem;
    }

    .card-button .card-text {
        display: none; /* Optional: hide for cleaner circular UI */
    }


    .list-group-item {
    border: none;
    padding: 0.75rem 0;
    font-size: 1rem;
  }

</style>
