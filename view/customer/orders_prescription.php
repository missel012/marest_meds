<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php");
?>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header" style="background-color: #6ccf54; color: white;">
      <h4 class="mb-0">➕ Add Order</h4>
    </div>
    <div class="card-body">
      <form action="add_order_process.php" method="POST" id="orderForm">
        <!-- Customer Name -->
        <div class="mb-3">
          <label class="form-label">Customer Name</label>
          <input type="text" class="form-control" name="customer_name"
            value="<?= htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']) ?>" readonly>
        </div>

        <!-- Select Item -->
        <div class="mb-3">
          <label class="form-label">Select Item</label>
          <select class="form-select" name="medicine_id" id="medicineSelect" required>
            <option value="" disabled selected>-- Select Medicine --</option>
            <?php
            $query = "SELECT inventoryId, brandName, quantity FROM inventory";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
              $id = $row['inventoryId'];
              $brand = htmlspecialchars($row['brandName']);
              $stock = (int)$row['quantity'];
              echo "<option value='$id' data-stock='$stock'>$brand (Stock: $stock)</option>";
            }
            ?>
          </select>
        </div>

        <!-- Quantity Input -->
        <div class="mb-3">
          <label class="form-label">Quantity</label>
          <input type="number" class="form-control" name="quantity" id="quantityInput" min="1" required>
          <div id="stockWarning" class="form-text text-danger d-none">⚠️ Over the limit</div>
        </div>

        <!-- Order Date -->
        <div class="mb-3">
          <label class="form-label">Order Date</label>
          <input type="date" class="form-control" name="order_date" value="<?= date('Y-m-d') ?>" required>
        </div>

        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" id="submitBtn">Save Order</button>
        <a href="orders_list.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

<!-- JavaScript for Stock Limit Validation -->
<script>
  const medicineSelect = document.getElementById('medicineSelect');
  const quantityInput = document.getElementById('quantityInput');
  const warning = document.getElementById('stockWarning');
  const submitBtn = document.getElementById('submitBtn');

  medicineSelect.addEventListener('change', () => {
    quantityInput.value = '';
    quantityInput.classList.remove('is-invalid');
    warning.classList.add('d-none');
    submitBtn.disabled = false;
  });

  quantityInput.addEventListener('input', () => {
    const selected = medicineSelect.options[medicineSelect.selectedIndex];
    const stock = parseInt(selected.getAttribute('data-stock'));
    const quantity = parseInt(quantityInput.value);

    if (quantity > stock) {
      quantityInput.classList.add('is-invalid');
      warning.classList.remove('d-none');
      submitBtn.disabled = true;
    } else {
      quantityInput.classList.remove('is-invalid');
      warning.classList.add('d-none');
      submitBtn.disabled = false;
    }
  });
</script>

<?php
include("./includes/footer.php");
?>
