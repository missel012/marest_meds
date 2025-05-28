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
include("../../db/config.php"); // Include database configuration

// Fetch total number of medicines
$medicine_query = "SELECT COUNT(*) AS total_medicines FROM inventory";
$medicine_result = mysqli_query($conn, $medicine_query);
$medicine_data = mysqli_fetch_assoc($medicine_result);
$total_medicines = $medicine_data['total_medicines'];

// Fetch number of medicines with quantity less than 30
$low_stock_query = "SELECT inventoryId, genericName, brandName, milligram, dosageForm, quantity, price FROM inventory WHERE quantity < 30";
$low_stock_result = mysqli_query($conn, $low_stock_query);
$low_stock_medicines = [];
while ($row = mysqli_fetch_assoc($low_stock_result)) {
    $low_stock_medicines[] = $row;
}

// Fetch orders for the current day
$current_date = date('Y-m-d');
$orders_query = "SELECT COUNT(*) AS orders_today FROM `order` WHERE DATE(datetime) = '$current_date'";
$orders_result = mysqli_query($conn, $orders_query);
$orders_data = mysqli_fetch_assoc($orders_result);
$orders_today = $orders_data['orders_today'];

// Fetch orders and calculate total revenue for each order
$query = "
    SELECT 
        DATE(o.datetime) AS order_date, 
        SUM(oi.total) AS total_revenue
    FROM 
        `order` o
    INNER JOIN 
        order_items oi 
    ON 
        o.orderId = oi.orderId
    GROUP BY 
        DATE(o.datetime)
    ORDER BY 
        DATE(o.datetime) ASC
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$graphData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $graphData[] = [
        'order_date' => $row['order_date'],
        'total_revenue' => $row['total_revenue']
    ];
}

// Pass graph data to JavaScript
?>
<script>
    const graphData = <?php echo json_encode($graphData); ?>;
</script>

<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<div class="row">


<!-- Home Page Content -->
<!-- Home Banners Section -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>About Us Banner Only</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .home-banner-container {
      width: 100%;
      max-width: 100%;
      overflow: hidden;
      border-radius: 0;
    }

    .about-us {
      position: relative;
    }

    .about-us img {
      width: 620%;
      height: 490px;
      object-fit: cover;
      display: block;
    }

    .about-us button {
      position: absolute;
      bottom: 120px;
      left: 330px;
      padding: 10px 45px;
      border: none;
      background-color: rgba(49, 247, 0, 0.69);
      color: white;
      border-radius: 10px;
      cursor: pointer;
      font-weight: bold;
      font-size: 16px;
    }
  </style>
</head>
<body>

  <!-- About Us Banner -->
  <div class="home-banner-container">
    <div class="about-us">
      <img src="../../assets/img/huhu.jpg" alt="About Us" />
      <button onclick="location.href='about-us.php'">Learn More About Us</button>
    </div>
  </div>

</body>
</html>

<style>
  .featured-categories-section {
    margin-top: 50px;
    margin-bottom: 20px;
    margin-left: 50px;
  }

  
</style> 
<!-- Featured Categories Section -->
<!-- Featured Categories Section -->
<div class="featured-categories-section">
 <h2>Featured Categories</h2></h2>

  </div>
</div>

  <div class="categories-container">
    <a href="#" class="category-box">
      <img src="../../assets/img/lol.jpg" alt="Analgesic" />
      <span>Analgesic</span>
    </a>
    <a href="#" class="category-box">
      <img src="../../assets/img/ant.jpg" alt="Antibiotic" />
      <span>Antibiotic</span>
    </a>
    <a href="#" class="category-box">
      <img src="../../assets/img/lol3.jpg" alt="Antidiabetic" />
      <span>Antidiabetic</span>
    </a>
    <a href="#" class="category-box">
      <img src="../../assets/img/lol.7.jpg" alt="Antihistamine" />
      <span>Antihistamine</span>
    </a>
    <a href="#" class="category-box">
      <img src="../../assets/img/lol2.jpg" alt="Antihypertensive" />
  
      <span>Antihypertensive</span>
    </a>
    <a href="#" class="category-box">
      <img src="../../assets/img/lol5.jpg" alt="NSAID" />
      <span>NSAID</span>
      
    </a>
  </div>
</div>
</div> <!-- end of featured-categories-section -->

<br><br> <!-- space before Popular Products -->


  <section class="popular-products-section" style="text-align: left; padding: 30px 20px;">
  <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 20px;margin-left: 40px; color:rgb(179, 2, 2);">
    Popular Products
  </h2>

  <!-- View More Button -->
  <div style="text-align: right; margin-top: 10px;">
    <button style="padding: 5px 10px; font-size: 14px; cursor: pointer; background-color: red; color: white; border: none; border-radius: 5px;">
      View More Products
    </button>
  </div>
</section>


</div>
</div>
  <div class="popular-products">
    <!-- First 5 popular products -->
    <div class="product-card">
      <img src="../../assets/img/brufen.jpg" alt="Analgesic">
      <span>Analgesic</span>
    </div>
    <div class="product-card">
      <img src="../../assets/img/amoxil.jpg" alt="Antibiotic">
      <span>Antibiotic</span>
    </div>
    <div class="product-card">
      <img src="../../assets/img/cetzine.png" alt="Antidiabetic">
      <span>Antidiabetic</span>
    </div>
    <div class="product-card">
      <img src="../../assets/img/calpol.jpg" alt="Antihistamine">
      <span>Antihistamine</span>
    </div>
    <div class="product-card">
      <img src="../../assets/img/dolex.jpg" alt="NSAID">
      <span>NSAID</span>
    </div>
  </div>



  <!-- Hidden additional products -->
  <div class="more-products hidden">
    <div class="product-card">
      <img src="https://via.placeholder.com/150" alt="Antihypertensive">
      <span>Antihypertensive</span>
    </div>
    <div class="product-card">
      <img src="https://via.placeholder.com/150" alt="Extra Medicine">
      <span>Extra Medicine</span>
    </div>
  </div>
</section>

  
</body>
</html>

