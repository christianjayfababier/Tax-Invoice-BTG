<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$staff_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM notifications WHERE staff_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($notifications);
