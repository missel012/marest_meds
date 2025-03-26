<?php
session_start(); // Ensure the session is started
ob_start(); // Start output buffering
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../db/config.php"); // Include database configuration

// Check if the user is logged in and the email is set in the session
if (!isset($_SESSION['email'])) {
    header("Location: ../../login.php"); // Redirect to login if not logged in
    exit();
}

// Get staff ID from URL
$staff_id = $_GET['staff_id'];

// Fetch staff data from the database, including the role from the users table
$query = "SELECT s.staff_name, s.email, s.shifts, u.role 
          FROM staff s 
          LEFT JOIN users u ON s.email = u.email 
          WHERE s.staff_id = '$staff_id'";
$result = mysqli_query($conn, $query);
$staff = mysqli_fetch_assoc($result);

// Fetch the current logged-in user's email
$current_user_email = $_SESSION['email'];

// Check if the current user is the viewed admin
$is_current_user_admin = ($staff && $staff['email'] === $current_user_email && $staff['role'] === 'admin');

// Handle form submission for updating staff
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $staff_name = $_POST['staff_name'];
  $email = $_POST['email'];
  $shifts = $_POST['shifts'];
  $role = $_POST['role'];

  // Update staff details in the staff table
  $update_staff_query = "UPDATE staff SET staff_name = '$staff_name', email = '$email', shifts = '$shifts' WHERE staff_id = '$staff_id'";
  mysqli_query($conn, $update_staff_query);

  // Update the role in the users table
  $update_role_query = "UPDATE users SET role = '$role' WHERE email = '$email'";
  mysqli_query($conn, $update_role_query);

  header("Location: staff.php");
  exit(); // Ensure no further code is executed after the redirect
}
?>

<style>
    /* Badge */
    .badge-custom {
        background-color: #6ccf54;
    }

    /* Button */
    .btn-custom {
        background-color: #db5c79;
        border-color: #db5c79;
        color: #fff;
    }

    .btn-custom:hover {
        background-color: #c04a67;
        border-color: #c04a67;
    }

    /* Modal Form */
    .modal-body {
        max-height: 400px;
        overflow-y: auto;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-title {
        font-weight: bold;
    }

    .form-label {
        font-weight: bold;
    }

    .btn-primary {
        background-color: #6ccf54 !important; /* Save button color */
        border-color: #6ccf54 !important;
    }

    .btn-primary:hover {
        background-color: #5ab94a !important;
        border-color: #5ab94a !important;
    }

    .btn-secondary {
        background-color: #db5c79 !important; /* Close button color */
        border-color: #db5c79 !important;
    }

    .btn-secondary:hover {
        background-color: #c04a67 !important;
        border-color: #c04a67 !important;
    }

    /* Make modal wider */
    .modal-dialog {
        max-width: 800px !important; /* Adjust width as needed */
    }
</style>

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
              <input type="text" class="form-control" id="staff_name" name="staff_name" value="<?php echo $staff['staff_name']; ?>" <?php echo $is_current_user_admin ? 'disabled' : ''; ?> required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo $staff['email']; ?>" <?php echo $is_current_user_admin ? 'disabled' : ''; ?> required>
            </div>
            <div class="mb-3">
              <label for="shifts" class="form-label">Shifts</label>
              <select class="form-select" id="shifts" name="shifts" <?php echo $is_current_user_admin ? 'disabled' : ''; ?> required>
                <option value="Day" <?php if ($staff['shifts'] == 'Day') echo 'selected'; ?>>Day</option>
                <option value="Afternoon" <?php if ($staff['shifts'] == 'Afternoon') echo 'selected'; ?>>Afternoon</option>
                <option value="Night" <?php if ($staff['shifts'] == 'Night') echo 'selected'; ?>>Night</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <select class="form-select" id="role" name="role" <?php echo $is_current_user_admin ? 'disabled' : ''; ?> required>
                <option value="admin" <?php if ($staff['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                <option value="staff" <?php if ($staff['role'] == 'staff') echo 'selected'; ?>>Staff</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='staff.php'">Cancel</button>
              <button type="submit" class="btn btn-primary" <?php echo $is_current_user_admin ? 'disabled' : ''; ?>>Update Staff</button>
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
