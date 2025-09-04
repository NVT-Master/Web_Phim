<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';

if (!isset($_GET['movie_id'])) {
    die("Thiếu movie_id");
}
$movieId = intval($_GET['movie_id']);

$conn = getDbConnection();

// Lấy thông tin phim
$stmt = $conn->prepare("SELECT * FROM movies WHERE id=?");
$stmt->bind_param("i", $movieId);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$movie) {
    die("Phim không tồn tại");
}

// Lấy suất chiếu
$stmt = $conn->prepare("SELECT * FROM showtimes WHERE movie_id=? ORDER BY start_time ASC");
$stmt->bind_param("i", $movieId);
$stmt->execute();
$showtimes = $stmt->get_result();
$stmt->close();

// Lấy danh sách ghế
$seats = $conn->query("SELECT * FROM seats ORDER BY row_label, seat_number");

$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt vé <?= htmlspecialchars($movie['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .seat-label {
            display: inline-block;
            margin: 3px;
            cursor: pointer;
        }

        .seat-checkbox {
            display: none;
            /* input ẩn đi */
        }

        .seat {
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 5px;
            background: #2ecc71;
            color: white;
        }

        .seat.selected {
            background: #f39c12;
        }
    </style>
</head>

<body class="bg-dark text-white">
    <div class="container mt-5">
        <h2>🎬 Đặt vé cho phim: <?= htmlspecialchars($movie['title']) ?></h2>
        <p><strong>Thể loại:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
        <p><strong>Thời lượng:</strong> <?= htmlspecialchars($movie['duration']) ?> phút</p>

        <form method="POST" action="../functions/book_process.php">
            <input type="hidden" name="movie_id" value="<?= $movieId ?>">

            <!-- Chọn suất chiếu -->
            <div class="mb-3">
                <label class="form-label">Chọn suất chiếu</label>
                <select name="showtime_id" class="form-select" required>
                    <?php while ($row = $showtimes->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>">
                            <?= date("d/m/Y H:i", strtotime($row['start_time'])) ?> - <?= number_format($row['price']) ?>đ
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Chọn ghế -->
            <h5>Chọn ghế</h5>
            <div>
                <?php while ($seat = $seats->fetch_assoc()): ?>
                    <label class="seat-label">
                        <input type="checkbox" name="seats[]" value="<?= $seat['id'] ?>" class="seat-checkbox">
                        <div class="seat available"><?= $seat['row_label'] . $seat['seat_number'] ?></div>
                    </label>
                    <?php if ($seat['seat_number'] == 8): ?>
                        <br>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>


            <button type="submit" class="btn btn-warning mt-3">Xác nhận đặt vé</button>
        </form>
    </div>

    <script>
        document.querySelectorAll(".seat-label").forEach(label => {
            const checkbox = label.querySelector(".seat-checkbox");
            const seatDiv = label.querySelector(".seat");

            label.addEventListener("click", (e) => {
                e.preventDefault(); // tránh click label auto toggle
                checkbox.checked = !checkbox.checked;
                seatDiv.classList.toggle("selected", checkbox.checked);
            });
        });
    </script>

</body>

</html>