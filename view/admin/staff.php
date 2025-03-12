<?php
ob_start(); // Start output buffering
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../db/config.php"); // Include database configuration

// Handle form submission for adding new staff
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
  $staff_name = $_POST['staff_name'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  $shifts = $_POST['shifts'];

  // Check if the email exists in the user table
  $email_check_query = "SELECT * FROM users WHERE email = '$email'";
  $email_check_result = mysqli_query($conn, $email_check_query);

  if (mysqli_num_rows($email_check_result) > 0) {
    // Check if the email already exists in the staff table
    $staff_check_query = "SELECT * FROM staff WHERE email = '$email'";
    $staff_check_result = mysqli_query($conn, $staff_check_query);

    if (mysqli_num_rows($staff_check_result) == 0) {
      // Determine the staff ID based on role
      $prefix = ($role == 'admin') ? 'A-' : 'S-';
      $query = "SELECT MAX(CAST(SUBSTRING(staff_id, 3) AS UNSIGNED)) AS max_id FROM staff WHERE staff_id LIKE '$prefix%'";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      $new_id = $prefix . str_pad($row['max_id'] + 1, 4, '0', STR_PAD_LEFT);

      // Insert new staff into the database
      $insert_query = "INSERT INTO staff (staff_name, staff_id, email, shifts) VALUES ('$staff_name', '$new_id', '$email', '$shifts')";
      mysqli_query($conn, $insert_query);
      $_SESSION['message'] = "Staff added successfully.";
      $_SESSION['code'] = "success";
    } else {
      $_SESSION['message'] = "Staff already added.";
      $_SESSION['code'] = "error";
    }
  } else {
    $_SESSION['message'] = "Can't add unregistered staff.";
    $_SESSION['code'] = "error";
  }
  header("Location: staff.php");
  exit();
}

// Handle delete staff
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $delete_query = "DELETE FROM staff WHERE staff_id = '$delete_id'";
  mysqli_query($conn, $delete_query);
  $_SESSION['message'] = "Staff deleted successfully.";
  $_SESSION['code'] = "success";
  header("Location: staff.php");
  exit();
}

// Determine current shift
date_default_timezone_set('Asia/Manila'); // Set the timezone
$current_hour = date('H');
if ($current_hour >= 8 && $current_hour < 12) {
    $current_shift = 'Day';
} elseif ($current_hour >= 12 && $current_hour < 17) {
    $current_shift = 'Afternoon';
} elseif ($current_hour >= 17 && $current_hour < 21) {
    $current_shift = 'Night';
} else {
    $current_shift = 'None';
}

// Fetch staff data from the database
$query = "SELECT staff_name, staff_id, email, shifts FROM staff";
$result = mysqli_query($conn, $query);
?>


<div class="pagetitle">
  <h1>List of Staffs</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">Staff</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">List of Staffs in Carmen Branch</h5>

          <div class="d-flex justify-content-between mb-3">
            <div>
              <input type="text" class="form-control" placeholder="Search for Staff" id="searchStaff" onkeyup="searchStaffFunction()">
            </div>
            <div>
              <button class="btn btn-primary" style="background: #DB5C79; border: none; color: white;" data-bs-toggle="modal" data-bs-target="#addStaffModal">+ Add Staff</button>
            </div>
          </div>

          <!-- Table with stripped rows -->
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Staff Name</th>
                <th>Staff ID</th>
                <th>Email</th>
                <th>Shifts</th>
                <th>On-Shift</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  $status_icon = ($row['shifts'] == $current_shift) ? '<i class="bi bi-check-circle" style="color: green;"></i>' : '<i class="bi bi-x-circle" style="color: red;"></i>';
                  echo "<tr>";
                  echo "<td>" . $row['staff_name'] . "</td>";
                  echo "<td>" . $row['staff_id'] . "</td>";
                  echo "<td>" . $row['email'] . "</td>";
                  echo "<td>" . $row['shifts'] . "</td>";
                  echo "<td>" . $status_icon . "</td>";
                  echo '<td>
                          <a href="edit_staff.php?staff_id=' . $row['staff_id'] . '" class="btn btn-warning btn-sm" style="background-color: #6CCF54; border: none;"><i class="bi bi-pencil-square"></i></a>
                          <a href="staff.php?delete_id=' . $row['staff_id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this staff?\')"><i class="bi bi-trash"></i></a>
                        </td>';
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='6'>No staff found</td></tr>";
              }
              ?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
  </div>
</section>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="addStaffModalLabel">Add New Staff</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="staff_name" class="form-label">Staff Name</label>
            <input type="text" class="form-control" id="staff_name" name="staff_name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="shifts" class="form-label">Shifts</label>
            <select class="form-select" id="shifts" name="shifts" required>
              <option value="Day">Day</option>
              <option value="Afternoon">Afternoon</option>
              <option value="Night">Night</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="add_staff">Add Staff</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include("./includes/footer.php");
ob_end_flush(); // Flush the output buffer
?>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function searchStaffFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchStaff");
  filter = input.value.toUpperCase();
  table = document.querySelector(".table");
  tr = table.getElementsByTagName("tr");

  for (i = 1; i < tr.length; i++) {
    tr[i].style.display = "none";
    td = tr[i].getElementsByTagName("td");
    for (var j = 0; j < td.length; j++) {
      if (td[j]) {
        txtValue = td[j].textContent || td[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
          break;
        }
      }
    }
  }
}

document.addEventListener("DOMContentLoaded", function() {
  const currentShift = "<?php echo $current_shift; ?>";
  const rows = document.querySelectorAll(".table tbody tr");
  rows.forEach(row => {
    const shiftCell = row.cells[3];
    if (shiftCell && shiftCell.textContent.trim() === currentShift) {
      row.classList.add("highlight");
    }
  });
});
</script>

<?php
if (isset($_SESSION['message']) && $_SESSION['code'] != '') {
  ?>
  <script>
    Swal.fire({
      icon: "<?php echo $_SESSION['code']; ?>",
      title: "<?php echo $_SESSION['message']; ?>"
    });
  </script>
  <?php
  unset($_SESSION['message']);
  unset($_SESSION['code']);
}
?>

<style>
  .highlight {
    background-color: #FFD700 !important;
  }
  .table-striped tbody tr:nth-of-type(odd) {
    background-color: #6CCF54 !important;
  }
  .table-striped tbody tr:nth-of-type(even) {
    background-color: #A3E4A7;
  }
</style>