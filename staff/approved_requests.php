<?php
session_start();
require_once '../config.php';

// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit;
}

// Fetch approved invoices for the logged-in staff
$staff_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM invoice_list WHERE staff_id = ? AND approval_status = 'Approved'");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$invoices = $result->fetch_all(MYSQLI_ASSOC);

$current_page = "approved_requests"; // Highlight the active menu
?>

<?php include '../includes/head.php'; ?>
<?php include '../includes/header.php'; ?>

<div class="content">
    <div class="container mt-4">
        <h1>Approved Invoices</h1>
        <?php if (!empty($invoices)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice Number</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $index => $invoice): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($invoice['tax_invoice_number']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['invoice_type']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['priority']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['approval_status']); ?></td>
                            <td><?php echo date('d M Y, H:i', strtotime($invoice['date_requested'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No approved invoices found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
