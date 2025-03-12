<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h1><span class="badge badge-custom"><?= htmlspecialchars($category) ?></span></h1>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Generic Name</th>
                                <th scope="col">Brand Name</th>
                                <th scope="col">Milligram</th>
                                <th scope="col">Dosage Form</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['genericName']) ?></td>
                                    <td><?= htmlspecialchars($item['brandName']) ?></td>
                                    <td><?= htmlspecialchars($item['milligram']) ?>ml</td>
                                    <td><?= htmlspecialchars($item['dosageForm']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>₱<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editInventoryModal" data-item='<?= json_encode($item) ?>'><i class="bi bi-pencil-square"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<!-- Edit Inventory Modal -->
<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInventoryModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editInventoryForm">
                    <input type="hidden" id="inventoryId" name="inventoryId">
                    <div class="mb-3">
                        <label for="editGenericName" class="form-label">Generic Name</label>
                        <input type="text" class="form-control" id="editGenericName" name="genericName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBrandName" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="editBrandName" name="brandName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMilligram" class="form-label">Milligram</label>
                        <input type="text" class="form-control" id="editMilligram" name="milligram" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDosageForm" class="form-label">Dosage Form</label>
                        <input type="text" class="form-control" id="editDosageForm" name="dosageForm" required>
                    </div>
                    <div class="mb-3">
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPrice" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="editPrice" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGroup" class="form-label">Group</label>
                        <input type="text" class="form-control" id="editGroup" name="group" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editInventoryModal = document.getElementById('editInventoryModal');
        const editInventoryForm = document.getElementById('editInventoryForm');

        editInventoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const item = JSON.parse(button.getAttribute('data-item'));

            document.getElementById('inventoryId').value = item.inventoryId;
            document.getElementById('editGenericName').value = item.genericName;
            document.getElementById('editBrandName').value = item.brandName;
            document.getElementById('editMilligram').value = item.milligram;
            document.getElementById('editDosageForm').value = item.dosageForm;
            document.getElementById('editQuantity').value = item.quantity;
            document.getElementById('editPrice').value = item.price;
            document.getElementById('editGroup').value = item.group;
        });

        editInventoryForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(editInventoryForm);

            fetch('update_inventory.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log('Raw response:', text);
                try {
                    return JSON.parse(text);
                } catch (error) {
                    throw new Error('Invalid JSON response');
                }
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating item: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating item: ' + error.message);
            });
        });
    });
</script>

<?php include("./includes/footer.php"); ?>

<style>
    /* Inventory */
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
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-primary:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
</style>