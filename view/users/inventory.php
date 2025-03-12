<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php"); // Ensure this file contains your database connection

// Fetch inventory items grouped by category
$query = "SELECT * FROM inventory ORDER BY `group` ASC, inventoryId ASC";
$result = mysqli_query($conn, $query);

$inventory = [];
while ($row = mysqli_fetch_assoc($result)) {
    $inventory[$row['group']][] = $row;
}
?>

<div class="pagetitle">
    <h1>Inventory</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active"><a href="inventory.php">Inventory</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <?php foreach ($inventory as $category => $items) : ?>
        <div class="card">
            <div class="card-body py-1">
                <h1 class="mt-4"><span class="badge badge-custom"><?= htmlspecialchars($category) ?></span></h1>

                <div id="carousel-<?= strtolower(str_replace(' ', '-', $category)) ?>" class="carousel slide mt-4">
                    <div class="carousel-inner">
                        <?php
                        $chunks = array_chunk($items, 4); // Display 4 items per carousel slide
                        foreach ($chunks as $index => $chunk) :
                        ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <div class="row">
                                    <?php foreach ($chunk as $item) : ?>
                                        <div class="col-lg-3">
                                            <div class="card mt-4">
                                                <div class="image-container">
                                                    <img src="<?= str_replace('C:/xampp/htdocs/datahan_eblacas/', '../../', $item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['brandName']) ?>">
                                                </div>
                                                <div class="card-body text-center">
                                                    <h5 class="card-title"><?= htmlspecialchars($item['genericName']) . ' ' . htmlspecialchars($item['brandName']) . ' ' . $item['milligram'] . 'ml ' . $item['dosageForm'] ?></h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">Quantity in Stock: <?= $item['quantity'] ?></h6>
                                                    <p class="card-text">₱<?= number_format($item['price'], 2) ?></p>
                                                </div>
                                                <button type="button" class="btn btn-custom">Check Details</button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($items) > 4) : ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?= strtolower(str_replace(' ', '-', $category)) ?>" data-bs-slide="prev" style="display: none;">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?= strtolower(str_replace(' ', '-', $category)) ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<?php include("./includes/footer.php"); ?>

<style>
    /* Inventory */
    /* Carousel */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        padding: 10px;
        width: 30px;
        height: 30px;
        transition: all 0.3s ease;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    .carousel-control-prev {
        left: -4%;
    }

    .carousel-control-next {
        right: -4%;
    }

    .carousel-control-prev-icon:hover,
    .carousel-control-next-icon:hover {
        background-color: rgba(0, 0, 0, 0.8);
        padding: 15px;
        width: 40px;
        height: 40px;
    }

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

    /* Image Container */
    .image-container {
        width: 100%;
        height: 200px; /* Set a fixed height for the image container */
        overflow: hidden;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures the image covers the container while maintaining aspect ratio */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const carousels = document.querySelectorAll('.carousel');

        carousels.forEach(carousel => {
            const prevButton = carousel.querySelector('.carousel-control-prev');
            const nextButton = carousel.querySelector('.carousel-control-next');
            const items = carousel.querySelectorAll('.carousel-item');

            if (items.length <= 1) {
                nextButton.style.display = 'none';
            }

            carousel.addEventListener('slide.bs.carousel', function (event) {
                const activeIndex = Array.from(items).indexOf(carousel.querySelector('.carousel-item.active'));
                const nextIndex = event.to;

                if (nextIndex === 0) {
                    prevButton.style.display = 'none';
                    nextButton.style.display = 'block';
                } else if (nextIndex === items.length - 1) {
                    nextButton.style.display = 'none';
                    prevButton.style.display = 'block';
                } else {
                    prevButton.style.display = 'block';
                    nextButton.style.display = 'block';
                }
            });
        });
    });
</script>