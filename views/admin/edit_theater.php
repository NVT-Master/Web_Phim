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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);

    if ($name && $address) {
        $stmt = $conn->prepare("UPDATE theaters SET name = ?, address = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $address, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật rạp thành công!";
            header("Location: theaters.php");
            exit();
        } else {
            $error = "Lỗi khi cập nhật rạp!";
        }
        $stmt->close();
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}

// Lấy thông tin rạp
$stmt = $conn->prepare("SELECT * FROM theaters WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$theater = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if (!$theater) {
    header("Location: theaters.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin rạp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1>✏️ Sửa thông tin rạp</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Tên rạp</label>
            <input type="text" name="name" class="form-control" 
                   value="<?= htmlspecialchars($theater['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <textarea name="address" class="form-control" required><?= htmlspecialchars($theater['address']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="theaters.php" class="btn btn-secondary">Hủy</a>
    </form>
</body>
</html>