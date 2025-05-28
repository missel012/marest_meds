<?php
// Ensure the session is started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Include SweetAlert library in the header
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php"); // Ensure this file contains your database connection

// Fetch user data from the database using the email stored in the session
if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $query = "SELECT firstName, lastName, email, profilePicture, birthday, gender, phoneNumber, role FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  // Update session variables with user data
  $_SESSION['firstName'] = $user['firstName'];
  $_SESSION['lastName'] = $user['lastName'];
  $_SESSION['email'] = $user['email'];
  $_SESSION['profilePicture'] = $user['profilePicture'];
  $_SESSION['birthday'] = $user['birthday'];
  $_SESSION['gender'] = $user['gender'];
  $_SESSION['phoneNumber'] = $user['phoneNumber'];
  $_SESSION['role'] = $user['role'];
} else {
  // Redirect to login if email is not set in the session
  header("Location: ../../login.php");
  exit();
}

// Ensure the upload directory exists
$uploadDir = '../../uploads/';
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
}

// Fetch user details from the session
$user = [
  'firstName' => $_SESSION['firstName'],
  'lastName' => $_SESSION['lastName'],
  'email' => $_SESSION['email'],
  'profilePicture' => $_SESSION['profilePicture'],
  'birthday' => $_SESSION['birthday'],
  'gender' => $_SESSION['gender'],
  'phoneNumber' => $_SESSION['phoneNumber'],
  'role' => $_SESSION['role']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProfile'])) {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $birthday = $_POST['birthday'];
  $gender = $_POST['gender'];
  $phoneNumber = $_POST['phoneNumber'];
  $email = $_POST['email'];

  // Get userId using email
  $getIdQuery = "SELECT userId FROM users WHERE email = ?";
  $idStmt = $conn->prepare($getIdQuery);
  $idStmt->bind_param("s", $email);
  $idStmt->execute();
  $idResult = $idStmt->get_result();
  $idRow = $idResult->fetch_assoc();
  $userId = $idRow['userId'];

  // Handle profile picture upload
  if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
    $fileName = basename($_FILES['profilePicture']['name']);
    $targetFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $targetFilePath)) {
      $profilePicture = $targetFilePath;
    } else {
      $profilePicture = $user['profilePicture'];
      echo "<script>
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: 'Failed to upload profile picture.',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      });
      </script>";
    }
  } else {
    $profilePicture = $user['profilePicture'];
  }

  // Update user details in the database
  $updateQuery = "UPDATE users SET firstName = ?, lastName = ?, birthday = ?, gender = ?, phoneNumber = ?, email = ?, profilePicture = ? WHERE userId = ?";
  $stmt = $conn->prepare($updateQuery);
  $stmt->bind_param("sssssssi", $firstName, $lastName, $birthday, $gender, $phoneNumber, $email, $profilePicture, $userId);

  if ($stmt->execute()) {
    // Update session with new values
    $_SESSION['firstName'] = $firstName;
    $_SESSION['lastName'] = $lastName;
    $_SESSION['email'] = $email;
    $_SESSION['birthday'] = $birthday;
    $_SESSION['gender'] = $gender;
    $_SESSION['phoneNumber'] = $phoneNumber;
    $_SESSION['profilePicture'] = $profilePicture;

    echo "<script>
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: 'Profile updated successfully.',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    }).then(() => {
      window.location = 'profile.php';
    });
    </script>";
  } else {
    echo "<script>
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: 'Error updating profile. Please try again.',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
    </script>";
  }
}

$errorMessage = null; // Initialize an error message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePassword'])) {
  $currentPassword = $_POST['currentPassword'];
  $newPassword = $_POST['newPassword'];
  $renewPassword = $_POST['renewPassword'];

  // Fetch the current password from the database using the email
  $passwordQuery = "SELECT userId, password FROM users WHERE email = ?";
  $stmt = $conn->prepare($passwordQuery);
  $stmt->bind_param("s", $_SESSION['email']);
  $stmt->execute();
  $result = $stmt->get_result();
  $userData = $result->fetch_assoc();

  if ($userData) {
    $userId = $userData['userId'];
    $userPassword = $userData['password'];

    // Check if the password is hashed
    if (!password_verify($currentPassword, $userPassword)) {
      if ($currentPassword !== $userPassword) {
        $errorMessage = "Current password is incorrect.";
      } else {
        // Hash the plain text password in the database
        $hashedPassword = password_hash($currentPassword, PASSWORD_DEFAULT);
        $updatePasswordQuery = "UPDATE users SET password = ? WHERE userId = ?";
        $stmt->prepare($updatePasswordQuery);
        $stmt->bind_param("si", $hashedPassword, $userId);
        $stmt->execute();
        $userPassword = $hashedPassword;
      }
    }

    if (!$errorMessage && password_verify($currentPassword, $userPassword)) {
      if ($newPassword === $renewPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePasswordQuery = "UPDATE users SET password = ? WHERE userId = ?";
        $stmt->prepare($updatePasswordQuery);
        $stmt->bind_param("si", $hashedPassword, $userId);

        if ($stmt->execute()) {
          echo "<script>
Swal.fire({
  toast: true,
  position: 'top-end',
  icon: 'success',
  title: 'Password changed successfully.',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true
}).then(() => {
  window.location = 'profile.php#profile-change-password';
});
</script>";
        } else {
          $errorMessage = "Error updating password. Please try again.";
        }
      } else {
        $errorMessage = "New passwords do not match.";
      }
    } elseif (!$errorMessage) {
      $errorMessage = "Current password is incorrect.";
    }
  } else {
    $errorMessage = "User not found.";
  }
}
?>

<?php if ($errorMessage): ?>
  <script>
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: '<?= htmlspecialchars($errorMessage) ?>',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    }).then(() => {
      document.querySelector('[data-bs-target="#profile-change-password"]').click();
    });
  </script>
<?php endif; ?>

<div class="pagetitle">
  <h1>User Profile</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item">User Profile</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section profile">
  <div class="row">
    <div class="col-xl-4">
      <div class="card text-center shadow-sm" style="border-radius: 15px; background-color: #DB5C79; color: #ffffff; position: relative; margin-top: 60px;">
        <div class="card-body" style="padding-top: 80px;">
          <div class="profile-picture-container" style="position: absolute; top: -60px; left: 50%; transform: translateX(-50%);">
            <img src="<?= $user['profilePicture'] && file_exists($user['profilePicture']) ? htmlspecialchars($user['profilePicture']) : '../../assets/img/default-user.png' ?>"
              alt="Profile Picture"
              class="rounded-circle shadow"
              style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #ffffff;">
          </div>
          <h2 class="card-title mb-1" style="margin-top: 40px; font-size: 1.5rem; color: #ffffff;"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></h2>
          <p class="text-light" style="font-size: 1rem;"><?= $user['role'] === 'admin' ? 'Admin' : 'Staff' ?></p>
          <div class="contact-info mt-3">
            <p><i class="bi bi-telephone-fill me-2"></i><?= htmlspecialchars($user['phoneNumber']) ?></p>
            <p><i class="bi bi-envelope-fill me-2"></i><?= htmlspecialchars($user['email']) ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="card shadow-sm" style="border-radius: 15px; border: 2px solid #6CCF54; background-color: #ffffff; color: #000000; margin-top: 60px;">
        <div class="card-body pt-3">
          <!-- Bordered Tabs -->
          <ul class="nav nav-tabs nav-tabs-bordered">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
            </li>
          </ul>
          <style>
            .nav-tabs .nav-link {
              color: #000000;
              /* Default color for inactive tabs */
              transition: color 0.3s ease, border-color 0.3s ease;
              /* Smooth transition for color and border */
            }

            .nav-tabs .nav-link.active {
              color: #6CCF54 !important;
              /* Color for active tab */
              border-color: #6CCF54 !important;
              /* Change underline color for active tab */
            }

            .nav-tabs .nav-link:hover {
              color: #6CCF54;
              /* Hover color for inactive tabs */
            }
          </style>
          <div class="tab-content pt-2">
            <div class="tab-pane fade show active profile-overview" id="profile-overview">
              <!-- Profile Details Section -->
              <h5 class="card-title text-center" style="font-size: 1.5rem; color: #000000;">Profile Details</h5>
              <div class="row">
                <div class="col-lg-3 col-md-4 label">Full Name</div>
                <div class="col-lg-9 col-md-8"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-md-4 label">Birthday</div>
                <div class="col-lg-9 col-md-8"><?= htmlspecialchars($user['birthday']) ?></div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-md-4 label">Gender</div>
                <div class="col-lg-9 col-md-8"><?= htmlspecialchars($user['gender']) ?></div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-md-4 label">Phone</div>
                <div class="col-lg-9 col-md-8"><?= htmlspecialchars($user['phoneNumber']) ?></div>
              </div>
              <div class="row">
                <div class="col-lg-3 col-md-4 label">Email</div>
                <div class="col-lg-9 col-md-8"><?= htmlspecialchars($user['email']) ?></div>
              </div>
            </div>
            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
              <!-- Profile Edit Form -->
              <form method="POST" enctype="multipart/form-data">
<div class="row mb-3">
  <label for="profilePicture" class="col-md-4 col-lg-3 col-form-label" style="color: #000000;">Profile Image</label>
  <div class="col-md-8 col-lg-9 text-center">
    <div style="width: 130px; height: 130px; margin: 0 auto 15px; overflow: hidden; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
      <img id="previewImage"
        src="<?= $user['profilePicture'] && file_exists($user['profilePicture']) ? htmlspecialchars($user['profilePicture']) : '../../assets/img/default-user.png' ?>" 
        alt="Profile Picture"
        style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    <input type="file" name="profilePicture" id="profilePictureInput" class="form-control mt-2">
  </div>
</div>
                <div class="row mb-3">
                  <label for="firstName" class="col-md-4 col-lg-3 col-form-label" style="color: #000000;">First Name</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="firstName" type="text" class="form-control" id="firstName" value="<?= htmlspecialchars($user['firstName']) ?>" required style="color: black;">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="lastName" class="col-md-4 col-lg-3 col-form-label" style="color: #000000;">Last Name</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="lastName" type="text" class="form-control" id="lastName" value="<?= htmlspecialchars($user['lastName']) ?>" required style="color: black;">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="birthday" class="col-md-4 col-lg-3 col-form-label" style="color: #000000;">Birthday</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="birthday" type="date" class="form-control" id="birthday" value="<?= htmlspecialchars($user['birthday']) ?>" required style="color: black;">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="gender" class="col-md-4 col-lg-3 col-form-label" style="color: #000000;">Gender</label>
                  <div class="col-md-8 col-lg-9">
                    <select name="gender" class="form-select" id="gender" required style="color: black;">
                      <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                      <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="phoneNumber" class="col-md-4 col-lg-3 col-form-label" style="color: #000000;">Phone</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="phoneNumber" type="text" class="form-control" id="phoneNumber" value="<?= htmlspecialchars($user['phoneNumber']) ?>" required style="color: black;">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="email" class="col-md-4 col-lg-3 col-form-label" style="color: #000000;">Email</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="email" type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" required style="color: black;" disabled>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" name="updateProfile" class="btn" style="background-color: #DB5C79; color: #ffffff;">Save Changes</button>
                </div>
              </form>
              <!-- End Profile Edit Form -->
            </div>
            <div class="tab-pane fade pt-3" id="profile-change-password">
              <!-- Change Password Form -->
              <form method="POST">
                <div class="row mb-3">
                  <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="currentPassword" type="password" class="form-control" id="currentPassword" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="newPassword" type="password" class="form-control" id="newPassword" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="renewPassword" type="password" class="form-control" id="renewPassword" required>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" name="changePassword" class="btn" style="background-color: #DB5C79; color: #ffffff;">Change Password</button>
                </div>
              </form>
              <!-- End Change Password Form -->
            </div>
          </div><!-- End Bordered Tabs -->
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  document.getElementById('profilePictureInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('previewImage').src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
</script>


<?php
include("./includes/footer.php");
?>