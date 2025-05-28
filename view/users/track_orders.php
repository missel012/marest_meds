<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php");
include("../../auth/authentication.php"); // Ensure this file contains your authentication logic

// Ensure the user is authenticated
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
if (!$email) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Unauthorized',
            text: 'Please log in to view your orders.',
            showConfirmButton: true
        }).then(() => {
            window.location.href = '/IT322/login.php';
        });
    </script>";
    exit;
}

// Fetch userId using the email
$stmtFetchUserId = $conn->prepare("SELECT userId FROM users WHERE email = ?");
$stmtFetchUserId->bind_param("s", $email);
$stmtFetchUserId->execute();
$stmtFetchUserId->bind_result($userId);
$stmtFetchUserId->fetch();
$stmtFetchUserId->close();

if (!$userId) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'User not found.',
            showConfirmButton: true
        }).then(() => {
            window.location.href = '/IT322/login.php';
        });
    </script>";
    exit;
}

// Cancel logic – runs before HTML renders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $trackId = (int)$_POST['trackId'];
    $reason = $conn->real_escape_string($_POST['reason']);

    $updateQuery = "
        UPDATE track_order
        SET status = 'cancelled', cancelReason = '$reason'
        WHERE trackId = $trackId AND userId = $userId AND status IN ('ordered', 'processing')
    ";

    if ($conn->query($updateQuery)) {
        echo "<script>
            window.onload = function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Order cancelled successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => location.href = window.location.href);
            };
        </script>";
    } else {
        echo "<script>
            window.onload = function() {
                Swal.fire('Error', 'Cancellation failed.', 'error');
            };
        </script>";
    }
}

// Fetch orders — exclude all canceled orders so none appear
$ordersQuery = "
    SELECT t.trackId, t.orderId, t.status, t.orderDateTime, t.estimatedDelivery, t.cancelReason
    FROM track_order t
    WHERE t.userId = $userId
      AND t.status != 'cancelled'
    ORDER BY t.orderDateTime DESC
";
$ordersResult = $conn->query($ordersQuery);
?>

<div class="pagetitle">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Track Orders</h1>
    </div>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active"><a href="track_orders.php">Track Orders</a></li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($ordersResult->num_rows === 0): ?>
                <div class="alert alert-info text-center" role="alert">
                    You have no active orders.
                </div>
            <?php else: ?>
                <?php while ($order = $ordersResult->fetch_assoc()): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Order #<?= htmlspecialchars($order['orderId']) ?></h5>
                            <p class="card-text">
                                <strong>Date:</strong> <?= date("M d, Y h:i A", strtotime($order['orderDateTime'])) ?><br>
                                <strong>Estimated Delivery:</strong> <?= $order['estimatedDelivery'] ? htmlspecialchars($order['estimatedDelivery']) : "N/A" ?><br>
                                <strong>Status:</strong> <span class="badge bg-warning text-dark"><?= htmlspecialchars($order['status']) ?></span>
                            </p>

                            <?php if ($order['cancelReason']): ?>
                                <p class="text-danger"><strong>❌ Cancel Reason:</strong> <?= htmlspecialchars($order['cancelReason']) ?></p>
                            <?php endif; ?>

                            <?php if (in_array($order['status'], ['ordered', 'processing'])): ?>
                                <button class="btn btn-outline-danger btn-sm" onclick="confirmCancel(<?= (int)$order['trackId'] ?>)">Cancel Order</button>
                            <?php endif; ?>

                            <table class="table table-bordered mt-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th> <!-- Added a column for the image -->
                                        <th>Generic</th>
                                        <th>Brand</th>
                                        <th>Dosage</th>
                                        <th>Form</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $itemsQuery = "
            SELECT oi.genericName, oi.brandName, oi.milligram, oi.dosageForm, oi.quantity, oi.price, oi.total, i.image
            FROM order_items oi
            LEFT JOIN inventory i ON oi.inventoryId = i.inventoryId
            WHERE oi.orderId = {$order['orderId']}
        ";
                                    $itemsResult = $conn->query($itemsQuery);
                                    while ($item = $itemsResult->fetch_assoc()):
                                    ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="data:image/jpeg;base64,<?= base64_encode($item['image']) ?>"
                                                        alt="Drug Image"
                                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;" />
                                                <?php else: ?>
                                                    <span>No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($item['genericName']) ?></td>
                                            <td><?= htmlspecialchars($item['brandName']) ?></td>
                                            <td><?= (int)$item['milligram'] ?> mg</td>
                                            <td><?= htmlspecialchars($item['dosageForm']) ?></td>
                                            <td><?= (int)$item['quantity'] ?></td>
                                            <td>₱<?= number_format($item['price'], 2) ?></td>
                                            <td>₱<?= number_format($item['total'], 2) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
include("./includes/footer.php");
?>

<style>
.table thead th {
    background-color: #EDFFE9; /* Light green background for the table header */
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmCancel(trackId) {
        Swal.fire({
            title: 'Cancel Order',
            html: `
            <div style="text-align: left;">
                <label for="reasonSelect">Reason for cancellation:</label><br>
                <select id="reasonSelect" class="form-select mt-2">
                    <option value="" disabled selected>Select a reason</option>
                    <option value="Wrong medicine">Wrong medicine</option>
                    <option value="Changed my mind">Changed my mind</option>
                    <option value="Ordered by mistake">Ordered by mistake</option>
                    <option value="Found a better price">Found a better price</option>
                    <option value="Delivery taking too long">Delivery taking too long</option>
                    <option value="Other">Other (please specify)</option>
                </select>
                <input type="text" id="customReason" class="form-control mt-2" placeholder="Enter your reason" style="display: none;" />
            </div>
        `,
            confirmButtonText: 'Submit',
            showCancelButton: true,
            preConfirm: () => {
                const select = document.getElementById('reasonSelect');
                const input = document.getElementById('customReason');
                const selected = select.value;
                const finalReason = selected === 'Other' ? input.value.trim() : selected;

                if (!finalReason) {
                    Swal.showValidationMessage('Please provide a reason.');
                    return false;
                }

                return finalReason;
            },
            didOpen: () => {
                const select = document.getElementById('reasonSelect');
                const input = document.getElementById('customReason');

                select.addEventListener('change', () => {
                    input.style.display = select.value === 'Other' ? 'block' : 'none';
                });
            }
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('trackId').value = trackId;
                document.getElementById('reason').value = result.value;
                document.getElementById('cancelForm').submit();
            }
        });
    }
</script>