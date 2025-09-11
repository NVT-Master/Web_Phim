<?php
require_once __DIR__ . '/../functions/db_connection.php';

$conn = getDbConnection();

// 1. Thêm rạp
$conn->query("INSERT INTO theaters (name, address) VALUES ('CGV Vincom', '123 Đường ABC')");

// 2. Thêm phòng
$conn->query("INSERT INTO rooms (theater_id, name) VALUES (1, 'Phòng 1')");

// 3. Thêm ghế (5 hàng × 8 ghế = 40 ghế)
for ($r = 'A'; $r <= 'E'; $r++) {
    for ($n = 1; $n <= 8; $n++) {
        $stmt = $conn->prepare("INSERT INTO seats (room_id, row_label, seat_number) VALUES (1, ?, ?)");
        $stmt->bind_param("si", $r, $n);
        $stmt->execute();
    }
}

// 4. Thêm phim
$conn->query("INSERT INTO movies (title, description, duration_min, rating, poster_url, release_date)
VALUES ('Avengers: Endgame', 'Bom tấn siêu anh hùng Marvel', 180, 'C13', 'poster.jpg', '2019-04-26')");

// 5. Thêm suất chiếu
$conn->query("INSERT INTO showtimes (movie_id, room_id, start_time, price)
VALUES (1, 1, '2025-09-05 19:30:00', 80000)");

echo "Seed OK";
