<?php
session_start();
require_once __DIR__ . '/../../functions/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: theaters.php");
    exit();
}

$id = intval($_GET['id']);
$conn = getDbConnection();

// Kiểm tra xem có phòng nào trong rạp không
$stmt = $conn->prepare("SELECT COUNT(*) as room_count FROM rooms WHERE theater_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($result['room_count'] > 0) {
    $_SESSION['error'] = "Không thể xóa rạp này vì đang có phòng chiếu!";
} else {
    $stmt = $conn->prepare("DELETE FROM theaters WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Xóa rạp thành công!";
    } else {
        $_SESSION['error'] = "Lỗi khi xóa rạp!";
    }
    $stmt->close();
}

$conn->close();
header("Location: theaters.php");
exit();