<?php
$message = isset($_GET['msg']) ? $_GET['msg'] : 'Unauthorized access.';
$type = isset($_GET['type']) ? $_GET['type'] : 'error';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/IT322/index.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Unauthorized</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background: #f8f9fa;">
<script>
    Swal.fire({
        icon: "<?php echo htmlspecialchars($type); ?>",
        title: "<?php echo htmlspecialchars($message); ?>",
        showConfirmButton: false,
        timer: 2000
    }).then(() => {
        window.location.href = "<?php echo htmlspecialchars($redirect); ?>";
    });
</script>
</body>
</html>
