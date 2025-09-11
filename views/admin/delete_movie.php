<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: movies.php");
    exit();
}

$id = intval($_GET['id']);
$conn = getDbConnection();

$stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $_SESSION['success'] = "Xóa phim thành công!";
} else {
    $_SESSION['error'] = "Lỗi khi xóa phim!";
}
$stmt->close();

header("Location: movies.php");
exit();
