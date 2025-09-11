<?php
session_start();
require_once __DIR__ . '/../../functions/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$id = intval($_GET['id']);
$conn = getDbConnection();

// Lấy room_id để redirect
$stmt = $conn->prepare("SELECT room_id FROM seats WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$seat = $result->fetch_assoc();
$stmt->close();

if (!$seat) {
    $_SESSION['error'] = "Ghế không tồn tại!";
    header("Location: rooms.php");
    exit();
}

// Kiểm tra xem ghế có đang được đặt không
$stmt = $conn->prepare("SELECT COUNT(*) as booking_count FROM booking_items WHERE seat_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($result['booking_count'] > 0) {
    $_SESSION['error'] = "Không thể xóa ghế này vì đã có người đặt!";
} else {
    $stmt = $conn->prepare("DELETE FROM seats WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Xóa ghế thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi xóa ghế!";
    }
    $stmt->close();
}

$conn->close();
header("Location: seats.php?room_id=" . $seat['room_id']);
exit();