<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
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
          <p>List of staffs in Carmen Branch</p>

          <div class="d-flex justify-content-between mb-3">
            <div>
              <input type="text" class="form-control" placeholder="Search for Staff" id="searchStaff">
            </div>
            <div>
              <button class="btn btn-primary" style="background:rgb(230, 207, 0); border: none">+ Add Staff</button>
            </div>
          </div>

          <!-- Table with stripped rows -->
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Staff Name</th>
                <th>Staff ID</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Marisol Datahan</td>
                <td>D06ID232435454</td>
                <td>marisol@gmail.com</td>
                <td>Cashier</td>
                <td><a href="#" class="btn btn-link">View Full Detail</a></td>
              </tr>
              <tr>
                <td>Krysel Tiempo</td>
                <td>D06ID232435451</td>
                <td>marisol@gmail.com</td>
                <td>Pharmacist</td>
                <td><a href="#" class="btn btn-link">View Full Detail</a></td>
              </tr>
              <tr>
                <td>Ezra Marinas</td>
                <td>D06ID232435452</td>
                <td>marisol@gmail.com</td>
                <td>Assistant</td>
                <td><a href="#" class="btn btn-link">View Full Detail</a></td>
              </tr>
              <tr>
                <td>Charlene Lusterio</td>
                <td>D06ID232435450</td>
                <td>marisol@gmail.com</td>
                <td>Pharmacist</td>
                <td><a href="#" class="btn btn-link">View Full Detail</a></td>
              </tr>
              <tr>
                <td>Therese Solangon</td>
                <td>D06ID232435455</td>
                <td>marisol@gmail.com</td>
                <td>Assistant</td>
                <td><a href="#" class="btn btn-link">View Full Detail</a></td>
              </tr>
              <tr>
                <td>Lordwell Abalde</td>
                <td>D06ID232435456</td>
                <td>marisol@gmail.com</td>
                <td>Cashier</td>
                <td><a href="#" class="btn btn-link">View Full Detail</a></td>
              </tr>
              <tr>
                <td>Mamma Mia</td>
                <td>D06ID232435457</td>
                <td>marisol@gmail.com</td>
                <td>Cashier</td>
                <td><a href="#" class="btn btn-link">View Full Detail</a></td>
              </tr>
              <tr>
                <td>John Smith</td>
                <td>D06ID232435458</td>
                <td>marisol@gmail.com</td>
                <td>Pharmacist</td>
                <td><a href="#" class="btn btn-link">View Full Detail</a></td>
              </tr>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
  </div>
</section>

<?php
include("./includes/footer.php");
?>