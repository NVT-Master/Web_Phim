<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['booking_id'])) {
    die("Yêu cầu không hợp lệ");
}

$bookingId = intval($_POST['booking_id']);
$conn = getDbConnection();

// Kiểm tra booking có tồn tại không
$stmt = $conn->prepare("SELECT status FROM bookings WHERE id = ?");
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Không tìm thấy đơn đặt vé");
}

// Chỉ update khi đang PENDING
if ($booking['status'] === 'PENDING') {
    $stmt = $conn->prepare("UPDATE bookings SET status = 'PAID' WHERE id = ?");
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

// Quay lại payment.php để xem kết quả
header("Location: ../cinema/payment.php?booking_id=" . $bookingId);
exit;
?>
