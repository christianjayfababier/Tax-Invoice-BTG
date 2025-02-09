<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "Request List";
require_once '../config.php';

// Filters
$status_filter = $_GET['status'] ?? '';
$search_query = $_GET['search'] ?? '';

// Build query with filters
$query = "SELECT * FROM invoice_list WHERE 1";

if (!empty($status_filter)) {
    $query .= " AND approval_status = '$status_filter'";
}

if (!empty($search_query)) {
    $query .= " AND (tax_invoice_number LIKE '%$search_query%' OR invoice_type LIKE '%$search_query%')";
}

$result = $conn->query($query);
?>

<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="content">
    <div class="container mt-4">
        <h1>All Requests</h1>

        <!-- Filter Form -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Pending" <?php echo $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Approved" <?php echo $status_filter == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="Denied" <?php echo $status_filter == 'Denied' ? 'selected' : ''; ?>>Denied</option>
                        <option value="Cancelled" <?php echo $status_filter == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by Invoice Number or Type" value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <!-- Request Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice Number</th>
                    <th>Invoice Type</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Date Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['tax_invoice_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['invoice_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['approval_status']); ?></td>
                    <td><?php echo htmlspecialchars($row['priority']); ?></td>
                    <td><?php echo date("Y-m-d H:i", strtotime($row['date_requested'])); ?></td>
                    <td>
                        <a href="review_invoice.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Review</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
