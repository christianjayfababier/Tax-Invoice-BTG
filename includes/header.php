<?php
require_once '../config.php';
// Fetch notifications for the logged-in staff
$staff_id = $_SESSION['user_id']; // Ensure 'user_id' is set during login
$stmt = $conn->prepare("SELECT * FROM notifications WHERE staff_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="topbar">

        <a class="navbar-brand" href="#">System Logo</a>
        <div class="d-flex align-items-center">
            <!-- Notification Bell Icon -->
            <div class="dropdown">
                <button class="btn btn-secondary position-relative" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <?php
                    // Count unread notifications
                    $unread_count = 0;
                    foreach ($notifications as $notification) {
                        if ($notification['is_read'] == 0) {
                            $unread_count++;
                        }
                    }
                    ?>
                    <?php if ($unread_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $unread_count; ?>
                        </span>
                    <?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="max-height: 300px; overflow-y: auto;">
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <li>
                                <a href="mark_notification.php?id=<?php echo $notification['id']; ?>" class="dropdown-item <?php echo $notification['is_read'] == 0 ? 'fw-bold' : ''; ?>">
                                    <?php echo htmlspecialchars($notification['message']); ?>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo date('d M Y, H:i', strtotime($notification['created_at'])); ?>
                                    </small>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="dropdown-item text-muted">No notifications</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- User Account Dropdown -->
            <div class="dropdown ms-3">
                <button class="btn btn-secondary dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $_SESSION['username']; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="../profile.php">My Account</a></li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
        

    </div>
</nav>
<div class="sidebar">
<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'upload_invoice') ? 'active' : ''; ?>" href="upload_invoice.php">Upload New Tax Invoice</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'pending_requests') ? 'active' : ''; ?>" href="pending_requests.php">Pending Requests</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'approved_requests') ? 'active' : ''; ?>" href="approved_requests.php">Approved Requests</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'for_review_requests') ? 'active' : ''; ?>" href="for_review_requests.php">For Review</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'cancelled_requests') ? 'active' : ''; ?>" href="cancelled_requests.php">Cancelled Requests</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($current_page == 'all_records') ? 'active' : ''; ?>" href="all_records.php">All Records</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../logout.php">Logout</a>
    </li>
</ul>
</div>