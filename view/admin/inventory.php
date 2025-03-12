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
    $group = ucfirst($row['group']);
    $inventory[$group][] = $row;
}
?>

<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Inventory</h1>
        <a href="#" class="btn btn-primary" style="background: #DB5C79; border: none" data-bs-toggle="modal" data-bs-target="#addInventoryModal">Add Item Stock</a>
    </div>
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
                    <h1><span class="badge badge-custom"><?= htmlspecialchars(ucfirst($category)) ?></span></h1>
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
                                        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editInventoryModal" data-id="<?= $item['inventoryId'] ?>" style="background-color: #6CCF54; border: none;"><i class="bi bi-pencil-square"></i></a>
                                        <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteInventoryModal" data-id="<?= $item['inventoryId'] ?>"><i class="bi bi-trash"></i></a>
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

<!-- Add Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1" aria-labelledby="addInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInventoryModalLabel">Add Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addInventoryForm">
                    <div class="mb-3">
                        <label for="genericName" class="form-label">Generic Name</label>
                        <input type="text" class="form-control" id="genericName" name="genericName" required>
                    </div>
                    <div class="mb-3">
                        <label for="brandName" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="brandName" name="brandName" required>
                    </div>
                    <div class="mb-3">
                        <label for="milligram" class="form-label">Milligram</label>
                        <input type="text" class="form-control" id="milligram" name="milligram" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosageForm" class="form-label">Dosage</label>
                        <input type="text" class="form-control" id="dosageForm" name="dosageForm" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="group" class="form-label">Group</label>
                        <select class="form-select" id="group" name="group" required>
                            <option value="analgesic">Analgesic</option>
                            <option value="antibiotic">Antibiotic</option>
                            <option value="antidiabetic">Antidiabetic</option>
                            <option value="antihistamine">Antihistamine</option>
                            <option value="antihypertensive">Antihypertensive</option>
                            <option value="NSAID">NSAID</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" class="form-control mt-2 d-none" id="otherGroup" name="otherGroup" placeholder="Please specify">
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                    <input type="hidden" id="editInventoryId" name="inventoryId">
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
                        <label for="editDosageForm" class="form-label">Dosage</label>
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
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Inventory Modal -->
<div class="modal fade" id="deleteInventoryModal" tabindex="-1" aria-labelledby="deleteInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteInventoryModalLabel">Delete Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <form id="deleteInventoryForm">
                    <input type="hidden" id="deleteInventoryId" name="inventoryId">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('group').addEventListener('change', function () {
        const otherGroupInput = document.getElementById('otherGroup');
        if (this.value === 'other') {
            otherGroupInput.classList.remove('d-none');
            otherGroupInput.setAttribute('required', 'required');
        } else {
            otherGroupInput.classList.add('d-none');
            otherGroupInput.removeAttribute('required');
        }
    });

    document.getElementById('addInventoryForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);
        if (document.getElementById('group').value === 'other') {
            formData.set('group', document.getElementById('otherGroup').value);
        }

        fetch('add_inventory.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error adding item: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding item: ' + error.message);
        });
    });

    document.getElementById('editInventoryForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('edit_inventory.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error editing item: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error editing item: ' + error.message);
        });
    });

    document.getElementById('deleteInventoryForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('delete_inventory.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting item: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting item: ' + error.message);
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const editInventoryModal = document.getElementById('editInventoryModal');
        const deleteInventoryModal = document.getElementById('deleteInventoryModal');

        editInventoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const inventoryId = button.getAttribute('data-id');

            fetch('get_inventory.php?id=' + inventoryId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editInventoryId').value = data.inventoryId;
                    document.getElementById('editGenericName').value = data.genericName;
                    document.getElementById('editBrandName').value = data.brandName;
                    document.getElementById('editMilligram').value = data.milligram;
                    document.getElementById('editDosageForm').value = data.dosageForm;
                    document.getElementById('editQuantity').value = data.quantity;
                    document.getElementById('editPrice').value = data.price;
                    document.getElementById('editGroup').value = data.group;
                })
                .catch(error => console.error('Error:', error));
        });

        deleteInventoryModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const inventoryId = button.getAttribute('data-id');
            document.getElementById('deleteInventoryId').value = inventoryId;
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