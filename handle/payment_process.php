<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$bookingId = $_POST['booking_id'] ?? null;
if (!$bookingId) {
    $_SESSION['error'] = "Thiếu thông tin thanh toán!";
    header("Location: ../index.php");
    exit;
}

$conn = getDbConnection();
$stmt = $conn->prepare("UPDATE bookings SET status = 'CONFIRMED' WHERE id = ?");
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION['success'] = "Thanh toán thành công!";
header("Location: ../views/cinema/payment.php?booking_id=" . $bookingId);
exit;