<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = "Admin Dashboard";
require_once '../config.php';

// Dashboard logic here...

include 'includes/head.php';
include 'includes/header.php';
?>

<div class="content">
    <div class="container mt-4">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <!-- Dashboard Content -->
    </div>
</div>

<?php include 'includes/footer.php'; ?>
