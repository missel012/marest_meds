<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php");

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$inventory = [];

if ($search != '') {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $query = "SELECT * FROM inventory WHERE genericName LIKE '%$searchSafe%' OR brandName LIKE '%$searchSafe%'";
    $result = mysqli_query($conn, $query);

    // Flatten all results into one group (e.g., "Search Result")
    $inventory['Search Result'] = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $inventory['Search Result'][] = $row;
    }
} else {
    $query = "SELECT * FROM inventory ORDER BY `group` ASC, inventoryId ASC";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $group = ucfirst($row['group']);
        $inventory[$group][] = $row;
    }   
}
?>

<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Inventory</h1>
        <!-- Remove Add Item Stock button for staff -->
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
                                <th scope="col">Image</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['genericName']) ?></td>
                                    <td><?= htmlspecialchars($item['brandName']) ?></td>
                                    <td><?= htmlspecialchars($item['milligram']) ?>mg</td>
                                    <td><?= htmlspecialchars($item['dosageForm']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>₱<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($item['image']); ?>"
                                                 alt="Product Image"
                                                 class="img-fluid mb-2"
                                                 style="width: 50px; height: 50px; object-fit: cover;" />
                                        <?php else: ?>
                                            <span class="text-muted">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editInventoryModal" data-id="<?= $item['inventoryId'] ?>" style="background-color: #6CCF54; border: none;"><i class="bi bi-pencil-square"></i></a>
                                        <!-- Remove delete action for staff -->
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
                <form id="addInventoryForm" enctype="multipart/form-data">
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
                        <input type="number" class="form-control" id="milligram" name="milligram" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosageForm" class="form-label">Dosage Form</label>
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
                        <input type="text" class="form-control mt-2 d-none" id="otherGroup" placeholder="Please specify">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewAddImage(event)">
                        <div id="addImagePreview" class="mt-2"></div>
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
<script>
function previewAddImage(event) {
    const preview = document.getElementById('addImagePreview');
    preview.innerHTML = '';
    if (event.target.files && event.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width:50px;height:50px;object-fit:cover;">`;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>

<!-- Edit Inventory Modal -->
<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInventoryModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editInventoryForm" enctype="multipart/form-data">
                    <input type="hidden" id="editInventoryId" name="inventoryId">
                    <div class="mb-3">
                        <label for="editGenericName" class="form-label">Generic Name</label>
                        <input type="text" class="form-control" id="editGenericName" name="genericName" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="editBrandName" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="editBrandName" name="brandName" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="editMilligram" class="form-label">Milligram</label>
                        <input type="number" class="form-control" id="editMilligram" name="milligram" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="editDosageForm" class="form-label">Dosage Form</label>
                        <input type="text" class="form-control" id="editDosageForm" name="dosageForm" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="editQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPrice" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="editPrice" name="price" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="editGroup" class="form-label">Group</label>
                        <select class="form-select" id="editGroup" name="group" disabled required>
                            <option value="analgesic">Analgesic</option>
                            <option value="antibiotic">Antibiotic</option>
                            <option value="antidiabetic">Antidiabetic</option>
                            <option value="antihistamine">Antihistamine</option>
                            <option value="antihypertensive">Antihypertensive</option>
                            <option value="NSAID">NSAID</option>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" class="form-control mt-2 d-none" id="editOtherGroup" placeholder="Please specify" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="editImage" class="form-label">Image</label>
                        <input type="file" class="form-control" id="editImage" name="image" accept="image/*" disabled>
                        <div id="currentImagePreview" class="mt-2"></div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('group').addEventListener('change', function() {
        const otherGroupInput = document.getElementById('otherGroup');
        if (this.value === 'other') {
            otherGroupInput.classList.remove('d-none');
            otherGroupInput.setAttribute('required', 'required');
        } else {
            otherGroupInput.classList.add('d-none');
            otherGroupInput.removeAttribute('required');
        }
    });

    document.getElementById('addInventoryForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        const groupSelect = document.getElementById('group');
        let groupValue = groupSelect.value;
        if (groupValue === 'other') {
            groupValue = document.getElementById('otherGroup').value.trim();
        }
        // Prevent empty group value
        if (!groupValue) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Group is required!',
                showConfirmButton: true,
                timer: 5000,
                timerProgressBar: true
            });
            return;
        }
        formData.set('group', groupValue);

        fetch('add_inventory.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.clone().json().catch(() => response.text()))
            .then(data => {
                if (typeof data === 'string') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error adding item!',
                        text: 'Server returned invalid JSON:\n' + data,
                        showConfirmButton: true,
                        timer: 10000,
                        timerProgressBar: true
                    });
                    return;
                }
                if (data.success) {
                    // Close the modal after successful add
                    const addModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addInventoryModal'));
                    addModal.hide();

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Inventory item added!',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error adding item!',
                        text: (data.error ? data.error : '') + (data.log ? "\n" + data.log : ''),
                        showConfirmButton: true,
                        timer: 10000,
                        timerProgressBar: true
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error adding item!',
                    text: error,
                    showConfirmButton: true,
                    timer: 10000,
                    timerProgressBar: true
                });
                console.error('Error:', error);
            });
    });

    document.getElementById('editInventoryForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        // Always set the group value from the fetched data, not from the disabled select
        let groupValue = '';
        const editOtherGroupInput = document.getElementById('editOtherGroup');
        if (!editOtherGroupInput.classList.contains('d-none') && editOtherGroupInput.value.trim() !== '') {
            groupValue = editOtherGroupInput.value.trim();
        } else {
            const editGroupSelect = document.getElementById('editGroup');
            groupValue = editGroupSelect.options[editGroupSelect.selectedIndex].value;
        }
        formData.set('group', groupValue);

        // Manually append disabled fields' values so they are included in the request
        formData.set('genericName', document.getElementById('editGenericName').value);
        formData.set('brandName', document.getElementById('editBrandName').value);
        formData.set('milligram', document.getElementById('editMilligram').value);
        formData.set('dosageForm', document.getElementById('editDosageForm').value);
        formData.set('price', document.getElementById('editPrice').value);

        fetch('edit_inventory.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the modal after successful update
                    const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editInventoryModal'));
                    editModal.hide();

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Inventory item updated!',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error updating item!',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error updating item!',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                console.error('Error:', error);
            });
    });

    document.getElementById('deleteInventoryForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('delete_inventory.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Inventory item deleted!',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    }).then(() => {
                        // Close the modal after success
                        const deleteModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteInventoryModal'));
                        deleteModal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error deleting item!',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error deleting item!',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                console.error('Error:', error);
            });
    });

    // Edit group select: show/hide other input
    document.getElementById('editGroup').addEventListener('change', function() {
        const otherGroupInput = document.getElementById('editOtherGroup');
        if (this.value === 'other') {
            otherGroupInput.classList.remove('d-none');
            otherGroupInput.setAttribute('required', 'required');
        } else {
            otherGroupInput.classList.add('d-none');
            otherGroupInput.removeAttribute('required');
        }
    });

    // On show edit modal, fetch and populate fields
    document.addEventListener('DOMContentLoaded', function() {
        const editInventoryModal = document.getElementById('editInventoryModal');
        const deleteInventoryModal = document.getElementById('deleteInventoryModal');

        editInventoryModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const inventoryId = button.getAttribute('data-id');

            fetch('get_inventory.php?id=' + inventoryId)
                .then(response => response.json())
                .then(data => {
                    // Populate all input fields with values from database
                    document.getElementById('editInventoryId').value = data.inventoryId;
                    document.getElementById('editGenericName').value = data.genericName;
                    document.getElementById('editBrandName').value = data.brandName;
                    document.getElementById('editMilligram').value = data.milligram;
                    document.getElementById('editDosageForm').value = data.dosageForm;
                    document.getElementById('editQuantity').value = data.quantity;
                    document.getElementById('editPrice').value = data.price;

                    // Set group select and handle "other"
                    const editGroupSelect = document.getElementById('editGroup');
                    const editOtherGroupInput = document.getElementById('editOtherGroup');
                    let found = false;
                    for (let i = 0; i < editGroupSelect.options.length; i++) {
                        if (editGroupSelect.options[i].value.toLowerCase() === data.group.toLowerCase()) {
                            editGroupSelect.value = data.group;
                            found = true;
                            break;
                        }
                    }
                    if (!found) {
                        editGroupSelect.value = 'other';
                        editOtherGroupInput.classList.remove('d-none');
                        editOtherGroupInput.value = data.group;
                        editOtherGroupInput.setAttribute('required', 'required');
                    } else {
                        editOtherGroupInput.classList.add('d-none');
                        editOtherGroupInput.value = '';
                        editOtherGroupInput.removeAttribute('required');
                    }

                    // Show current image preview if available (image is BLOB base64)
                    if (data.image) {
                        document.getElementById('currentImagePreview').innerHTML =
                            `<img src="data:image/jpeg;base64,${data.image}" alt="Current Image" style="width:50px;height:50px;object-fit:cover;">`;
                    } else {
                        document.getElementById('currentImagePreview').innerHTML = `<span class="text-muted">No Image</span>`;
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        deleteInventoryModal.addEventListener('show.bs.modal', function(event) {
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
        background-color: #6ccf54 !important;
        /* Save button color */
        border-color: #6ccf54 !important;
    }

    .btn-primary:hover {
        background-color: #5ab94a !important;
        border-color: #5ab94a !important;
    }

    .btn-secondary {
        background-color: #db5c79 !important;
        /* Close button color */
        border-color: #db5c79 !important;
    }

    .btn-secondary:hover {
        background-color: #c04a67 !important;
        border-color: #c04a67 !important;
    }

    /* Make modal wider */
    .modal-dialog {
        max-width: 800px !important;
        /* Adjust width as needed */
    }
</style>