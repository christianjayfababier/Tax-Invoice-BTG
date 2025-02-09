<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "Staff List";
require_once '../config.php';

// Fetch all staff accounts
$result = $conn->query("SELECT id, username, email, created_at FROM users WHERE role = 'staff'");

?>

<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="content">
    <div class="container mt-4">
        <h1>Staff List</h1>
        <p>Manage all staff accounts below.</p>

        <a href="add_staff.php" class="btn btn-primary mb-3">Add New Staff</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo isset($row['created_at']) ? date("Y-m-d", strtotime($row['created_at'])) : "N/A"; ?></td>
                    <td>
                        <a href="edit_staff.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_staff.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
