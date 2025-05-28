<?php
ob_start(); // Start output buffering
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../db/config.php"); // Include database configuration

// Handle form submission for adding new staff
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
  $staff_name_email = $_POST['staff_name_email'];
  $role = $_POST['role'];
  $shifts = $_POST['shifts'];

  // Extract name and email from the selected option
  preg_match('/^(.*?)\s\((.*?)\)$/', $staff_name_email, $matches);
  $staff_name = $matches[1]; // Extracted name
  $email = $matches[2]; // Extracted email

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

      // Update the role in the users table
      $user_role = ($role === 'admin') ? 'admin' : 'staff';
      $update_user_query = "UPDATE users SET role = '$user_role' WHERE email = '$email'";
      mysqli_query($conn, $update_user_query);

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
  $search = isset($_GET['search']) ? trim($_GET['search']) : '';
  if ($search != '') {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $query = "SELECT staff_name, staff_id, email, shifts FROM staff WHERE staff_name LIKE '%$searchSafe%' OR email LIKE '%$searchSafe%'";
  } else {
    $query = "SELECT staff_name, staff_id, email, shifts FROM staff";
  }
  $result = mysqli_query($conn, $query);
}

// Handle delete staff
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];

  // Get the email of the staff to be deleted
  $get_email_query = "SELECT email FROM staff WHERE staff_id = '$delete_id'";
  $get_email_result = mysqli_query($conn, $get_email_query);
  $staff_email = '';
  if ($get_email_result && mysqli_num_rows($get_email_result) > 0) {
    $row = mysqli_fetch_assoc($get_email_result);
    $staff_email = $row['email'];
  }

  // Delete staff from staff table
  $delete_query = "DELETE FROM staff WHERE staff_id = '$delete_id'";
  $delete_result = mysqli_query($conn, $delete_query);

  // If deleted, update user role to 'user'
  if ($delete_result && $staff_email) {
    $update_role_query = "UPDATE users SET role = 'user' WHERE email = '$staff_email'";
    mysqli_query($conn, $update_role_query);
    $_SESSION['message'] = "Staff deleted successfully.";
    $_SESSION['code'] = "success";
  } else {
    $_SESSION['message'] = "Failed to delete staff.";
    $_SESSION['code'] = "error";
  }
  header("Location: staff.php");
  exit();
}

// Handle role assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assignRole'])) {
  $email = $_POST['email']; // Use email instead of ID
  $role = $_POST['role']; // 'admin' or 'staff'

  // Update the role in the staff table
  $updateStaffQuery = "UPDATE staff SET role = ? WHERE email = ?";
  $stmt = $conn->prepare($updateStaffQuery);
  $stmt->bind_param("ss", $role, $email);

  if ($stmt->execute()) {
    // Update the role in the users table
    $userRole = ($role === 'admin') ? 'admin' : 'staff';
    $updateUserQuery = "UPDATE users SET role = ? WHERE email = ?";
    $stmtUser = $conn->prepare($updateUserQuery);
    $stmtUser->bind_param("ss", $userRole, $email);

    if ($stmtUser->execute()) {
      $_SESSION['message'] = "Role assigned successfully.";
      $_SESSION['code'] = "success";
    } else {
      $_SESSION['message'] = "Failed to update role in users table.";
      $_SESSION['code'] = "error";
    }
  } else {
    $_SESSION['message'] = "Failed to assign role.";
    $_SESSION['code'] = "error";
  }

  header("location: staff.php");
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

// Fetch users with role 'user' only for add staff dropdown
$unassigned_users_query = "SELECT firstName, lastName, email FROM users WHERE role = 'user'";
$unassigned_users_result = mysqli_query($conn, $unassigned_users_query);

// Fetch staff with role 'staff' for the table
// $query = "SELECT staff_name, staff_id, email, shifts FROM staff WHERE email IN (SELECT email FROM users WHERE role = 'staff')";
// $result = mysqli_query($conn, $query);

// Show all staff regardless of user role
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
          <h5 class="card-title">List of Staffs</h5>

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
                          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteStaffModal" data-staff-id="' . $row['staff_id'] . '"><i class="bi bi-trash"></i></button>
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
            <label for="staff_name_email" class="form-label">Staff Name and Email</label>
            <select class="form-select" id="staff_name_email" name="staff_name_email" required>
              <option value="" disabled selected>Select Staff</option>
              <?php while ($user = mysqli_fetch_assoc($unassigned_users_result)) : ?>
                <option value="<?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName'] . ' (' . $user['email'] . ')') ?>">
                  <?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName'] . ' (' . $user['email'] . ')') ?>
                </option>
              <?php endwhile; ?>
            </select>
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

<!-- Modify Staff Modal -->
<div class="modal fade" id="modifyStaffModal" tabindex="-1" aria-labelledby="modifyStaffModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title" id="modifyStaffModalLabel">Modify Staff Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="email" class="form-label">Staff Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="assignRole">Update Role</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Staff Modal -->
<div class="modal fade" id="deleteStaffModal" tabindex="-1" aria-labelledby="deleteStaffModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteStaffModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this staff?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteStaffBtn" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<?php
include("./includes/footer.php");
ob_end_flush(); // Flush the output buffer
?>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['message']) && $_SESSION['code'] != ''): ?>
  <script>
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: "<?php echo $_SESSION['code']; ?>",
      title: "<?php echo $_SESSION['message']; ?>",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    }).then(() => {
      window.location = "staff.php";
    });
  </script>
  <?php unset($_SESSION['message'], $_SESSION['code']); ?>
<?php endif; ?>

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

  // Attach event to set staff id on modal
  const deleteStaffModal = document.getElementById('deleteStaffModal');
  deleteStaffModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const staffId = button.getAttribute('data-staff-id');
    const confirmDeleteBtn = deleteStaffModal.querySelector('#confirmDeleteStaffBtn');
    confirmDeleteBtn.href = 'staff.php?delete_id=' + staffId;
  });

  // Add search feature for user dropdown in Add Staff Modal
  document.addEventListener("DOMContentLoaded", function() {
    const select = document.getElementById('staff_name_email');
    if (select) {
      // Create a search input above the select
      const searchInput = document.createElement('input');
      searchInput.type = 'text';
      searchInput.className = 'form-control mb-2';
      searchInput.placeholder = 'Type to search user...';
      select.parentNode.insertBefore(searchInput, select);

      searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        for (let i = 0; i < select.options.length; i++) {
          const option = select.options[i];
          if (i === 0) continue; // Skip the "Select Staff" option
          const text = option.text.toLowerCase();
          option.style.display = text.includes(filter) ? '' : 'none';
        }
        // Optionally, reset selection if filtered out
        if (select.selectedIndex > 0 && select.options[select.selectedIndex].style.display === 'none') {
          select.selectedIndex = 0;
        }
      });
    }
  });
</script>