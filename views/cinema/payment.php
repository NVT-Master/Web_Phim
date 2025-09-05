<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';

if (!isset($_GET['booking_id'])) {
    die("Thiếu booking_id");
}
$bookingId = intval($_GET['booking_id']);

$conn = getDbConnection();

// Lấy thông tin booking
$stmt = $conn->prepare("
    SELECT b.id, b.status, b.total_amount, m.title, s.start_time
    FROM bookings b
    JOIN showtimes s ON b.showtime_id = s.id
    JOIN movies m ON s.movie_id = m.id
    WHERE b.id = ?
");
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Không tìm thấy đơn đặt vé");
}

// Lấy danh sách ghế
$stmt = $conn->prepare("
    SELECT se.row_label, se.seat_number
    FROM booking_items bi
    JOIN seats se ON bi.seat_id = se.id
    WHERE bi.booking_id = ?
");
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$seats = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán vé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<div class="container mt-5">
    <h2>💳 Thanh toán vé xem phim</h2>
    <p><strong>Phim:</strong> <?= htmlspecialchars($booking['title']) ?></p>
    <p><strong>Suất chiếu:</strong> <?= date('d/m/Y H:i', strtotime($booking['start_time'])) ?></p>
    <p><strong>Ghế đã đặt:</strong>
        <?php
        $seatNames = [];
        foreach ($seats as $seat) {
            $seatNames[] = $seat['row_label'] . $seat['seat_number'];
        }
        echo implode(', ', $seatNames);
        ?>
    </p>
    <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_amount']) ?>đ</p>
    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($booking['status']) ?></p>
    <!-- Thêm nút thanh toán nếu trạng thái là PENDING -->
    <?php if ($booking['status'] === 'PENDING'): ?>
        <form method="POST" action="/WebPhim/handle/payment_process.php">
            <input type="hidden" name="booking_id" value="<?= $bookingId ?>">
            <button type="submit" class="btn btn-success">Xác nhận thanh toán</button>
        </form>
    <?php else: ?>
        <div class="alert alert-success">Bạn đã thanh toán thành công!</div>
    <?php endif; ?>
</div>
</body>
</html>
