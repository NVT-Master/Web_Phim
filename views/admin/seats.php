<?php
session_start();
require_once __DIR__ . '/../../functions/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['room_id'])) {
    header("Location: rooms.php");
    exit();
}

$roomId = intval($_GET['room_id']);
$conn = getDbConnection();

// Lấy thông tin phòng và rạp
$stmt = $conn->prepare("
    SELECT r.*, t.name as theater_name 
    FROM rooms r
    JOIN theaters t ON r.theater_id = t.id
    WHERE r.id = ?
");
$stmt->bind_param("i", $roomId);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$room) {
    die("Phòng không tồn tại");
}

// Lấy danh sách ghế
$stmt = $conn->prepare("SELECT * FROM seats WHERE room_id = ? ORDER BY row_label, seat_number");
$stmt->bind_param("i", $roomId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý ghế - <?= htmlspecialchars($room['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .seat-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 10px;
            margin: 20px 0;
        }
        .seat {
            padding: 10px;
            text-align: center;
            background: #e9ecef;
            border-radius: 5px;
        }
    </style>
</head>
<body class="p-4">
    <h1>🪑 Quản lý ghế</h1>
    <h4>Rạp: <?= htmlspecialchars($room['theater_name']) ?> - <?= htmlspecialchars($room['name']) ?></h4>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="add_seat.php?room_id=<?= $roomId ?>" class="btn btn-success">➕ Thêm ghế mới</a>
        <a href="rooms.php" class="btn btn-secondary">Quay lại danh sách phòng</a>
    </div>

    <div class="seat-grid">
        <?php while ($seat = $result->fetch_assoc()): ?>
            <div class="seat">
                <strong><?= htmlspecialchars($seat['row_label']) ?><?= $seat['seat_number'] ?></strong>
                <div class="mt-2">
                    <a href="edit_seat.php?id=<?= $seat['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                    <a href="#" onclick="deleteSeat(<?= $seat['id'] ?>)" class="btn btn-danger btn-sm">❌</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function deleteSeat(id) {
            if (confirm('Bạn có chắc muốn xóa ghế này?')) {
                window.location.href = 'delete_seat.php?id=' + id;
            }
        }
    </script>
</body>
</html>