<?php
session_start();
require_once __DIR__ . '/../../functions/db_connection.php';

$conn = getDbConnection();

// Lấy danh sách phòng
$rooms = [];
$res = $conn->query("SELECT r.id, r.name, t.name AS theater_name 
                     FROM rooms r 
                     JOIN theaters t ON r.theater_id = t.id 
                     ORDER BY t.name, r.name");
while ($row = $res->fetch_assoc()) {
    $rooms[] = $row;
}

$roomId = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

// Thêm ghế
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_seat'])) {
    $rowLabel = strtoupper(trim($_POST['row_label']));
    $seatNumber = intval($_POST['seat_number']);
    if ($roomId > 0 && $rowLabel !== '' && $seatNumber > 0) {
        $stmt = $conn->prepare("INSERT INTO seats (room_id, row_label, seat_number) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $roomId, $rowLabel, $seatNumber);
        $stmt->execute();
        $stmt->close();
        header("Location: rooms.php?room_id=" . $roomId);
        exit;
    }
}

// Xóa ghế
if (isset($_GET['delete_seat'])) {
    $seatId = intval($_GET['delete_seat']);
    $stmt = $conn->prepare("DELETE FROM seats WHERE id = ?");
    $stmt->bind_param("i", $seatId);
    $stmt->execute();
    $stmt->close();
    header("Location: rooms.php?room_id=" . $roomId);
    exit;
}

// Lấy danh sách ghế
$seats = [];
if ($roomId > 0) {
    $stmt = $conn->prepare("SELECT * FROM seats WHERE room_id = ? ORDER BY row_label, seat_number");
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý ghế</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .seat-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            max-width: 600px;
        }

        .seat {
            width: 60px;
            height: 60px;
            background: #2ecc71;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border-radius: 8px;
        }

        .seat .delete-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            border: none;
            color: white;
            font-size: 14px;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-4">
        <h2 class="mb-4">🎟️ Quản lý ghế trong phòng</h2>

        <!-- Chọn phòng -->
        <form method="GET" class="mb-4">
            <label class="form-label">Chọn phòng chiếu</label>
            <select name="room_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Chọn phòng --</option>
                <?php foreach ($rooms as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= $roomId == $r['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['theater_name'] . " - " . $r['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($roomId > 0): ?>
            <!-- Form thêm ghế -->
            <form method="POST" class="card p-3 mb-4">
                <h5>➕ Thêm ghế mới</h5>
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="row_label" class="form-control" placeholder="Hàng (A, B, C...)" required>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="seat_number" class="form-control" placeholder="Số ghế (1, 2...)"
                            required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="add_seat" class="btn btn-success w-100">Thêm ghế</button>
                    </div>
                </div>
            </form>

            <!-- Hiển thị ghế dạng ô vuông -->
            <div class="seat-grid">
                <?php if (count($seats) > 0): ?>
                    <?php foreach ($seats as $seat): ?>
                        <div class="seat">
                            <?= htmlspecialchars($seat['row_label'] . $seat['seat_number']) ?>
                            <a href="rooms.php?room_id=<?= $roomId ?>&delete_seat=<?= $seat['id'] ?>"
                                onclick="return confirm('Xóa ghế này?')">
                                <button type="button" class="delete-btn">×</button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>❌ Chưa có ghế nào trong phòng này.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>