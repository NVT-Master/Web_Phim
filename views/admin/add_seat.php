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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rowLabel = trim($_POST['row_label']);
    $seatNumber = intval($_POST['seat_number']);

    if ($rowLabel && $seatNumber > 0) {
        $conn = getDbConnection();
        
        // Kiểm tra ghế đã tồn tại chưa
        $stmt = $conn->prepare("SELECT id FROM seats WHERE room_id = ? AND row_label = ? AND seat_number = ?");
        $stmt->bind_param("isi", $roomId, $rowLabel, $seatNumber);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($exists) {
            $error = "Ghế này đã tồn tại trong phòng!";
        } else {
            $stmt = $conn->prepare("INSERT INTO seats (room_id, row_label, seat_number) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $roomId, $rowLabel, $seatNumber);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Thêm ghế thành công!";
                header("Location: seats.php?room_id=" . $roomId);
                exit();
            } else {
                $error = "Lỗi khi thêm ghế!";
            }
            $stmt->close();
        }
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
    <title>Thêm ghế mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1>➕ Thêm ghế mới</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Hàng</label>
            <input type="text" name="row_label" class="form-control" maxlength="1" 
                   pattern="[A-Z]" title="Vui lòng nhập 1 chữ cái in hoa" required>
            <div class="form-text">Nhập 1 chữ cái in hoa (A-Z)</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Số ghế</label>
            <input type="number" name="seat_number" class="form-control" 
                   min="1" max="99" required>
        </div>
        <button type="submit" class="btn btn-success">Thêm ghế</button>
        <a href="seats.php?room_id=<?= $roomId ?>" class="btn btn-secondary">Hủy</a>
    </form>
</body>
</html>