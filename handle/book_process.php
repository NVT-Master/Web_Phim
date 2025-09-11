<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /WebPhim/index.php");
    exit;
}

$movieId = $_POST['movie_id'] ?? null;
$showtimeId = $_POST['showtime_id'] ?? null;
$seats = $_POST['seats'] ?? [];

if (!$movieId || !$showtimeId || empty($seats)) {
    $_SESSION['error'] = "Bạn chưa chọn suất chiếu hoặc ghế!";
    header("Location: /WebPhim/views/cinema/book.php?movie_id=$movieId&showtime_id=$showtimeId");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Bạn cần đăng nhập để đặt vé!";
    header("Location: /WebPhim/views/auth/login.php");
    exit;
}

$conn = getDbConnection();

// Kiểm tra ghế đã bị đặt chưa
$placeholders = implode(',', array_fill(0, count($seats), '?'));
$params = array_merge([$showtimeId], $seats);
$types = str_repeat('i', count($params));

$sql = "SELECT seat_id FROM booking_items 
        INNER JOIN bookings ON booking_items.booking_id = bookings.id
        WHERE bookings.showtime_id = ? AND seat_id IN ($placeholders)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ Lỗi prepare: " . $conn->error);
}
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$booked = [];
while ($row = $result->fetch_assoc()) {
    $booked[] = $row['seat_id'];
}
$stmt->close();

if (!empty($booked)) {
    $_SESSION['error'] = "Một hoặc nhiều ghế bạn chọn đã bị đặt. Vui lòng chọn lại!";
    header("Location: /WebPhim/views/cinema/book.php?movie_id=$movieId&showtime_id=$showtimeId");
    exit;
}

// Tính tổng tiền (giả sử 50k/ghế)
$totalAmount = count($seats) * 50000;

// Thêm booking
$stmt = $conn->prepare("
    INSERT INTO bookings (user_id, showtime_id, total_amount, status) 
    VALUES (?, ?, ?, 'PENDING')
");
if (!$stmt) {
    die("❌ Lỗi prepare: " . $conn->error);
}
$userId = $_SESSION['user_id'];
$stmt->bind_param("iii", $userId, $showtimeId, $totalAmount);
if (!$stmt->execute()) {
    die("❌ Lỗi SQL: " . $stmt->error);
}
$bookingId = $stmt->insert_id;
$stmt->close();

// Thêm ghế đã đặt vào booking_items
$stmt = $conn->prepare("INSERT INTO booking_items (booking_id, seat_id) VALUES (?, ?)");
if (!$stmt) {
    die("❌ Lỗi prepare: " . $conn->error);
}
foreach ($seats as $seatId) {
    $stmt->bind_param("ii", $bookingId, $seatId);
    if (!$stmt->execute()) {
        die("❌ Lỗi SQL: " . $stmt->error);
    }
}
$stmt->close();

$conn->close();

// ✅ Chuyển hướng sang trang thanh toán
header("Location: /WebPhim/views/cinema/payment.php?booking_id=" . $bookingId);
exit;
