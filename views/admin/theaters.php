<?php
session_start();
require_once __DIR__ . '/../../functions/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}

$conn = getDbConnection();
$result = $conn->query("SELECT t.*, COUNT(r.id) as room_count 
                       FROM theaters t
                       LEFT JOIN rooms r ON t.id = r.theater_id
                       GROUP BY t.id");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý rạp chiếu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1>🏢 Quản lý rạp chiếu</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <a href="add_theater.php" class="btn btn-success mb-3">➕ Thêm rạp mới</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên rạp</th>
                <th>Địa chỉ</th>
                <th>Số phòng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= $row['room_count'] ?></td>
                <td>
                    <a href="edit_theater.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                    <a href="rooms.php?theater_id=<?= $row['id'] ?>" class="btn btn-info btn-sm">🚪 Phòng</a>
                    <a href="#" onclick="deleteTheater(<?= $row['id'] ?>)" class="btn btn-danger btn-sm">❌ Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function deleteTheater(id) {
            if (confirm('Bạn có chắc muốn xóa rạp này?')) {
                window.location.href = 'delete_theater.php?id=' + id;
            }
        }
    </script>
</body>
</html>