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
                                                    <h5 class="card-title">
                                                        <?= htmlspecialchars($item['genericName']) . ' ' . htmlspecialchars($item['brandName']) . ' ' . $item['milligram'] . 'ml ' . $item['dosageForm'] ?>
                                                    </h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">Quantity in Stock: <?= $item['quantity'] ?></h6>
                                                    <p class="card-text">₱<?= number_format($item['price'], 2) ?></p>
                                                </div>
                                                <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#detailsModal" data-item='<?= json_encode($item) ?>'>Check Details</button>
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

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Medicine Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modal-image" src="" class="img-fluid mb-3" alt="" style="width: 300px; height: 300px; object-fit: cover;">
                <h5 id="modal-title"></h5>
                <h6 class="text-muted">Quantity in Stock: <span id="modal-quantity"></span></h6>
                <p class="card-text">₱<span id="modal-price"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-update">Update</button>
                <button type="button" class="btn btn-primary btn-save-changes" style="display: none;">Save changes</button>
            </div>
        </div>
    </div>
</div><!-- End Details Modal -->

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

    /* Save Changes Button */
    .btn-save-changes {
        background-color: #6ccf54;
        border-color: #6ccf54;
        color: #fff;
    }

    .btn-save-changes:hover {
        background-color: #5bbd4a;
        border-color: #5bbd4a;
    }

    /* Image Container */
    .image-container {
        width: 100%;
        height: 300px;
        /* Increased height for the image container */
        overflow: hidden;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Ensures the image covers the container while maintaining aspect ratio */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('detailsModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const item = JSON.parse(button.getAttribute('data-item'));

            document.getElementById('modal-title').textContent = `${item.genericName} ${item.brandName} ${item.milligram}ml ${item.dosageForm}`;
            document.getElementById('modal-quantity').textContent = item.quantity;
            document.getElementById('modal-price').textContent = Number(item.price).toFixed(2);
            document.getElementById('modal-image').src = item.image.replace('C:/xampp/htdocs/datahan_eblacas/', '../../');
            document.getElementById('modal-image').alt = item.brandName;

            const updateButton = document.querySelector('.btn-update');
            const saveChangesButton = document.querySelector('.btn-save-changes');

            updateButton.addEventListener('click', function() {
                const modalBody = document.querySelector('.modal-body');
                modalBody.innerHTML = `
                    <div class="row mb-3">
                        <label for="genericName" class="col-sm-2 col-form-label">Generic Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="genericName" value="${item.genericName}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="brandName" class="col-sm-2 col-form-label">Brand Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="brandName" value="${item.brandName}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="milligram" class="col-sm-2 col-form-label">Milligram</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="milligram" value="${item.milligram}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="dosageForm" class="col-sm-2 col-form-label">Dosage Form</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="dosageForm" value="${item.dosageForm}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="quantity" class="col-sm-2 col-form-label">Quantity</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="quantity" value="${item.quantity}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="price" class="col-sm-2 col-form-label">Price</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="price" value="${item.price}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="formFile" class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="file" id="formFile">
                        </div>
                    </div>
                `;

                updateButton.style.display = 'none';
                saveChangesButton.style.display = 'block';
            });

            saveChangesButton.addEventListener('click', function() {
                const updatedItem = {
                    genericName: document.getElementById('genericName').value,
                    brandName: document.getElementById('brandName').value,
                    milligram: document.getElementById('milligram').value,
                    dosageForm: document.getElementById('dosageForm').value,
                    quantity: document.getElementById('quantity').value,
                    price: document.getElementById('price').value,
                    image: document.getElementById('image').value
                };

                // Here you can add the code to save the updatedItem to the database

                // Update the modal content with the new values
                document.getElementById('modal-title').textContent = `${updatedItem.genericName} ${updatedItem.brandName} ${updatedItem.milligram}ml ${updatedItem.dosageForm}`;
                document.getElementById('modal-quantity').textContent = updatedItem.quantity;
                document.getElementById('modal-price').textContent = Number(updatedItem.price).toFixed(2);
                document.getElementById('modal-image').src = updatedItem.image.replace('C:/xampp/htdocs/datahan_eblacas/', '../../');
                document.getElementById('modal-image').alt = updatedItem.brandName;

                saveChangesButton.style.display = 'none';
                updateButton.style.display = 'block';
            });
        });
    });
</script>