<?php
include("./includes/header.php");
include("./includes/topbar.php");
include("./includes/sidebar.php");
include("../../dB/config.php");

// Cancel logic – runs before HTML renders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $trackId = (int)$_POST['trackId'];
    $reason = $conn->real_escape_string($_POST['reason']);

    $updateQuery = "
        UPDATE track_order
        SET status = 'cancelled', cancelReason = '$reason'
        WHERE trackId = $trackId AND status IN ('ordered', 'processing')
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

// Load orders — exclude all canceled orders so none appear
$userId = 1; // Replace with your session user ID
$ordersQuery = "
    SELECT t.trackId, t.orderId, t.status, t.orderDateTime, t.estimatedDelivery, t.cancelReason
    FROM track_order t
    WHERE t.userId = $userId
      AND t.status != 'cancelled'
    ORDER BY t.orderDateTime DESC
";
$ordersResult = $conn->query($ordersQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Orders</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f9f9f9; }
        .order-box { background: #fff; padding: 15px; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; text-transform: capitalize; }
        .ordered { background: orange; color: white; }
        .processing { background: gold; }
        .shipping { background: blue; color: white; }
        .delivered { background: green; color: white; }
        .completed { background: darkgreen; color: white; }
        .cancelled, .rejected { background: red; color: white; }
        .cancel-btn {
            background: #d9534f;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .cancel-btn:hover {
            background: #c9302c;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<h2>🛒 Your Orders</h2>

<?php if ($ordersResult->num_rows === 0): ?>
    <p>You have no active orders.</p>
<?php else: ?>
    <?php while ($order = $ordersResult->fetch_assoc()): ?>
        <div class="order-box">
            <strong>Order #<?= htmlspecialchars($order['orderId']) ?></strong><br>
            Date: <?= date("M d, Y h:i A", strtotime($order['orderDateTime'])) ?><br>
            Estimated Delivery: <?= $order['estimatedDelivery'] ? htmlspecialchars($order['estimatedDelivery']) : "N/A" ?><br>
            Status: <span class="status-badge <?= htmlspecialchars($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span><br>

            <?php if ($order['cancelReason']): ?>
                <strong>❌ Cancel Reason:</strong> <?= htmlspecialchars($order['cancelReason']) ?><br>
            <?php endif; ?>

            <?php if (in_array($order['status'], ['ordered', 'processing'])): ?>
                <button class="cancel-btn" onclick="confirmCancel(<?= (int)$order['trackId'] ?>)">Cancel Order</button>
            <?php endif; ?>

            <table>
                <thead>
                    <tr><th>Generic</th><th>Brand</th><th>Dosage</th><th>Form</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                </thead>
                <tbody>
                <?php
                    $itemsQuery = "
                        SELECT oi.genericName, oi.brandName, oi.milligram, oi.dosageForm, oi.quantity, oi.price, oi.total
                        FROM order_items oi
                        WHERE oi.orderId = {$order['orderId']}
                    ";
                    $itemsResult = $conn->query($itemsQuery);
                    while ($item = $itemsResult->fetch_assoc()):
                ?>
                    <tr>
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
    <?php endwhile; ?>
<?php endif; ?>

<!-- Hidden form for submitting cancellation -->
<form id="cancelForm" method="POST" style="display: none;">
    <input type="hidden" name="trackId" id="trackId">
    <input type="hidden" name="reason" id="reason">
    <input type="hidden" name="cancel_order" value="1">
</form>

<script>
function confirmCancel(trackId) {
    Swal.fire({
        title: 'Cancel Order',
        html: `
            <div style="text-align: left;">
                <label for="reasonSelect">Reason for cancellation:</label><br>
                <select id="reasonSelect" style="width: 100%; padding: 6px; font-size: 14px; margin-top: 4px;">
                    <option value="" disabled selected>Select a reason</option>
                    <option value="Wrong medicine">Wrong medicine</option>
                    <option value="Changed my mind">Changed my mind</option>
                    <option value="Ordered by mistake">Ordered by mistake</option>
                    <option value="Found a better price">Found a better price</option>
                    <option value="Delivery taking too long">Delivery taking too long</option>
                    <option value="Other">Other (please specify)</option>
                </select>
                <input type="text" id="customReason" placeholder="Enter your reason" 
                       style="display: none; width: 100%; padding: 6px; font-size: 14px; margin-top: 8px;" />
            </div>
        `,
        width: 400,
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

</body>
</html>

<?php include("./includes/footer.php"); ?>
