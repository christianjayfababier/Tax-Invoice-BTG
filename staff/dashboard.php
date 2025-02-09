<?php
session_start();
require_once '../config.php';

// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'staff') {
    header("Location: ../login.php");
    exit;
}

// Fetch unread notifications for the logged-in staff
$staff_id = $_SESSION['user_id']; // Ensure 'user_id' is set in session during login
$stmt = $conn->prepare("SELECT * FROM notifications WHERE staff_id = ? AND is_read = 0 ORDER BY created_at DESC");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);

$current_page = "dashboard"; // Highlight the active menu
?>

<?php include '../includes/head.php'; ?>
<?php include '../includes/header.php'; ?>

<div class="content">
    <div class="container mt-4">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

        <h3>Notifications</h3>
        <?php if (!empty($notifications)): ?>
            <ul class="list-group">
                <?php foreach ($notifications as $notification): ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($notification['message']); ?>
                        <span class="text-muted small">
                            (<?php echo date('d M Y, H:i', strtotime($notification['created_at'])); ?>)
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted">No new notifications.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
