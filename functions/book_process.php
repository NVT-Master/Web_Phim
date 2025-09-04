<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Phương thức không hợp lệ");
}

if (!isset($_POST['showtime_id']) || empty($_POST['seats'])) {
    die("Bạn chưa chọn suất chiếu hoặc ghế");
}

$showtimeId = intval($_POST['showtime_id']);
$seatIds = $_POST['seats'];
$userId = $_SESSION['user_id'] ?? 1; // giả sử user_id = 1

$conn = getDbConnection();

// Lấy giá vé
$stmt = $conn->prepare("SELECT price FROM showtimes WHERE id=?");
$stmt->bind_param("i", $showtimeId);
$stmt->execute();
$result = $stmt->get_result();
$showtime = $result->fetch_assoc();
$stmt->close();

if (!$showtime) {
    die("Suất chiếu không tồn tại");
}
$price = $showtime['price'];
$totalAmount = $price * count($seatIds);

// Tạo booking
$stmt = $conn->prepare("INSERT INTO bookings (user_id, showtime_id, status, total_amount) VALUES (?, ?, 'PENDING', ?)");
$stmt->bind_param("iid", $userId, $showtimeId, $totalAmount);
$stmt->execute();
$bookingId = $stmt->insert_id;
$stmt->close();

// Thêm booking_items
$stmt = $conn->prepare("INSERT INTO booking_items (booking_id, seat_id, price) VALUES (?, ?, ?)");
foreach ($seatIds as $seatId) {
    $sid = intval($seatId);
    $stmt->bind_param("iid", $bookingId, $sid, $price);
    $stmt->execute();
}
$stmt->close();

$conn->close();

// Chuyển sang trang thanh toán
header("Location: ../cinema/payment.php?booking_id=" . $bookingId);
exit;
