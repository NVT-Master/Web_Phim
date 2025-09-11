<?php
session_start();
require_once __DIR__ . '/../../functions/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}

$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $genre = trim($_POST['genre']);
    $duration = intval($_POST['duration']);

    if ($title !== "" && $genre !== "" && $duration > 0) {
        $stmt = $conn->prepare("INSERT INTO movies (title, genre, duration) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $genre, $duration);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Thêm phim thành công!";
            header("Location: movies.php");
            exit();
        } else {
            $error = "Lỗi khi thêm phim!";
        }
        $stmt->close();
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm phim mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1>➕ Thêm phim mới</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Tên phim</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thể loại</label>
            <input type="text" name="genre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thời lượng (phút)</label>
            <input type="number" name="duration" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="movies.php" class="btn btn-secondary">Hủy</a>
    </form>
</body>
</html>
