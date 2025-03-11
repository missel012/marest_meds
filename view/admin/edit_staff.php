<?php
ob_start(); // Start output buffering
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../db/config.php"); // Include database configuration

// Get staff ID from URL
$staff_id = $_GET['staff_id'];

// Fetch staff data from the database
$query = "SELECT * FROM staff WHERE staff_id = '$staff_id'";
$result = mysqli_query($conn, $query);
$staff = mysqli_fetch_assoc($result);

// Handle form submission for updating staff
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $staff_name = $_POST['staff_name'];
  $email = $_POST['email'];
  $shifts = $_POST['shifts'];

  // Update staff details in the database
  $update_query = "UPDATE staff SET staff_name = '$staff_name', email = '$email', shifts = '$shifts' WHERE staff_id = '$staff_id'";
  mysqli_query($conn, $update_query);
  header("Location: staff.php");
  exit(); // Ensure no further code is executed after the redirect
}
?>

<div class="pagetitle">
  <h1>Edit Staff</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="staff.php">Staff</a></li>
      <li class="breadcrumb-item active">Edit Staff</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Edit Staff Details</h5>

          <!-- Edit Staff Form -->
          <form method="POST" action="">
            <div class="mb-3">
              <label for="staff_name" class="form-label">Staff Name</label>
              <input type="text" class="form-control" id="staff_name" name="staff_name" value="<?php echo $staff['staff_name']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo $staff['email']; ?>" required>
            </div>
            <div class="mb-3">
              <label for="shifts" class="form-label">Shifts</label>
              <select class="form-select" id="shifts" name="shifts" required>
                <option value="Day" <?php if ($staff['shifts'] == 'Day') echo 'selected'; ?>>Day</option>
                <option value="Afternoon" <?php if ($staff['shifts'] == 'Afternoon') echo 'selected'; ?>>Afternoon</option>
                <option value="Night" <?php if ($staff['shifts'] == 'Night') echo 'selected'; ?>>Night</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" onclick="window.location.href='staff.php'">Cancel</button>
              <button type="submit" class="btn btn-primary">Update Staff</button>
            </div>
          </form>
          <!-- End Edit Staff Form -->

        </div>
      </div>

    </div>
  </div>
</section>

<?php
include("./includes/footer.php");
ob_end_flush(); // Flush the output buffer
?>
