<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
?>

<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Track Orders</h1>
    </div>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Track Orders</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Order Tracking</h4>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="order_id" class="form-control" placeholder="Enter Order ID to track..." required>
                        <button class="btn btn-primary" type="submit">Track</button>
                    </div>
                </form>

                <!-- Tracking Info (Mock Data) -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h5>Tracking Information for Order #1234</h5>
                        <p><strong>Status:</strong> Shipping</p>
                        <p><strong>Address:</strong> 123 Sample Street, City</p>
                        <p><strong>Phone:</strong> 0912-345-6789</p>
                        <p><strong>Estimated Delivery:</strong> 2025-06-03</p>

                        <!-- Status Progress Bar -->
                        <div class="progress mt-4">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%">Ordered</div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%">Processing</div>
                            <div class="progress-bar bg-warning text-dark" role="progressbar" style="width: 25%">Shipping</div>
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 25%">Delivered</div>
                        </div>

                        <!-- Visual Status Tracker -->
                        <div class="d-flex justify-content-between mt-3">
                            <small>Ordered</small>
                            <small>Processing</small>
                            <small><strong>Shipping</strong></small>
                            <small>Delivered</small>
                        </div>
                    </div>
                </div>

                <!-- Optionally: More history or notes here -->
            </div>
        </div>
    </div>
</section>

<?php include("./includes/footer.php"); ?>

<style>
.progress {
    height: 30px;
}
.progress-bar {
    line-height: 30px;
    font-weight: bold;
}
</style>
