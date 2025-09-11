<?php
session_start();
require_once __DIR__ . '/../../functions/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);

    if ($name && $address) {
        $conn = getDbConnection();
        $stmt = $conn->prepare("INSERT INTO theaters (name, address) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $address);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Thêm rạp thành công!";
            header("Location: theaters.php");
            exit();
        } else {
            $error = "Lỗi khi thêm rạp!";
        }
        $stmt->close();
        $conn->close();
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm rạp mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1>➕ Thêm rạp chiếu mới</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Tên rạp</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="theaters.php" class="btn btn-secondary">Hủy</a>
    </form>
</body>
</html>