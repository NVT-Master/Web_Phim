<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /WEBPHIM/views/auth/login.php");
    exit;
}

require_once __DIR__ . '/../../functions/db_connection.php';
require_once __DIR__ . '/../../functions/movie_functions.php';
require_once __DIR__ . '/../../functions/showtime_functions.php';
require_once __DIR__ . '/../../functions/seat_functions.php';

if (!isset($_GET['movie_id'])) {
    die("Thiếu movie_id");
}
$movieId = intval($_GET['movie_id']);

$movie = getMovieById($movieId);
if (!$movie) {
    die("Phim không tồn tại");
}

$showtimes = getShowtimesByMovie($movieId);

$showtimeId = isset($_GET['showtime_id']) ? intval($_GET['showtime_id']) : 0;
$roomId = 0;
$seats = [];
$bookedSeats = [];

if ($showtimeId > 0) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM showtimes WHERE id = ?");
    $stmt->bind_param("i", $showtimeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $showtime = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    if ($showtime) {
        $roomId = $showtime['room_id'];
        $seats = getSeatsByRoom($roomId);
        $bookedSeats = getBookedSeats($showtimeId);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt vé <?= htmlspecialchars($movie['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            text-align: center;
            line-height: 40px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
        }

        .available {
            background: #2ecc71;
            color: white;
        }

        .booked {
            background: #e74c3c;
            color: white;
            cursor: not-allowed;
        }

        .selected {
            background: #f39c12 !important;
        }
    </style>
</head>

<body class="bg-dark text-white">
    <div class="container mt-5">
        <h2>🎬 Đặt vé cho phim: <?= htmlspecialchars($movie['title']) ?></h2>
        <p><strong>Thể loại:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
        <p><strong>Thời lượng:</strong> <?= htmlspecialchars($movie['duration']) ?> phút</p>

        <!-- Form chọn suất chiếu (GET) -->
        <form method="GET" action="">
            <input type="hidden" name="movie_id" value="<?= $movieId ?>">
            <div class="mb-3">
                <label class="form-label">Chọn suất chiếu</label>
                <select name="showtime_id" class="form-select" required onchange="this.form.submit()">
                    <option value="">-- Chọn suất chiếu --</option>
                    <?php foreach ($showtimes as $row): ?>
                        <option value="<?= $row['id'] ?>" <?= $showtimeId == $row['id'] ? 'selected' : '' ?>>
                            <?= date("d/m/Y H:i", strtotime($row['start_time'])) ?> - <?= number_format($row['price']) ?>đ
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <!-- Form chọn ghế (POST) -->
        <?php if ($roomId > 0 && count($seats) > 0): ?>
        <form method="POST" action="/WEBPHIM/handle/book_process.php">
            <input type="hidden" name="movie_id" value="<?= $movieId ?>">
            <input type="hidden" name="showtime_id" value="<?= $showtimeId ?>">
            <h5>Chọn ghế</h5>
            <div>
                <?php foreach ($seats as $seat):
                    $seatCode = $seat['row_label'] . $seat['seat_number'];
                    $isBooked = in_array($seat['id'], $bookedSeats);
                ?>
                    <label style="display:inline-block">
                        <input type="checkbox" name="seats[]" value="<?= $seat['id'] ?>" style="display:none;" <?= $isBooked ? 'disabled' : '' ?>>
                        <div class="seat <?= $isBooked ? 'booked' : 'available' ?>">
                            <?= $seatCode ?>
                        </div>
                    </label>
                    <?php if ($seat['seat_number'] == 8): ?><br><?php endif; ?>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-warning mt-3">Xác nhận đặt vé</button>
        </form>
        <?php elseif ($showtimeId > 0): ?>
            <div class="alert alert-warning mt-3">Không có ghế nào trong phòng này!</div>
        <?php endif; ?>
    </div>

    <script>
        document.querySelectorAll(".seat.available").forEach(seatDiv => {
            const checkbox = seatDiv.parentElement.querySelector("input[type=checkbox]");
            seatDiv.addEventListener("click", () => {
                if (seatDiv.classList.contains("selected")) {
                    seatDiv.classList.remove("selected");
                    checkbox.checked = false;
                } else {
                    seatDiv.classList.add("selected");
                    checkbox.checked = true;
                }
            });
        });
    </script>
</body>
</html>
