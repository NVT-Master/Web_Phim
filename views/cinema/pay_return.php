<?php
require_once __DIR__ . '/../functions/db_connection.php';

if (!isset($_GET['status']) || !isset($_GET['booking_id'])) {
    die("Thiếu dữ liệu");
}

$status = $_GET['status'];
$bookingId = intval($_GET['booking_id']);

$conn = getDbConnection();

if ($status === 'ok') {
    $stmt = $conn->prepare("UPDATE bookings SET status='CONFIRMED' WHERE id=?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $stmt->close();
    echo "<h3>✅ Thanh toán thành công! Vé đã được xác nhận.</h3>";
} else {
    $stmt = $conn->prepare("UPDATE bookings SET status='CANCELLED' WHERE id=?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $stmt->close();
    echo "<h3>❌ Thanh toán thất bại! Vé đã bị hủy.</h3>";
}

$conn->close();

echo '<p><a href="my_tickets.php">Xem vé của tôi</a></p>';
