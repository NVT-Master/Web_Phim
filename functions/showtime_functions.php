<?php
require_once __DIR__ . '/db_connection.php';

// Lấy các suất chiếu theo ID phim
function getShowtimesByMovie($movie_id) {
    $conn = getDbConnection();
    $stmt = mysqli_prepare($conn, 
        "SELECT s.id, s.start_time, s.price, r.name as room_name, t.name as theater_name
         FROM showtimes s
         JOIN rooms r ON s.room_id = r.id
         JOIN theaters t ON r.theater_id = t.id
         WHERE s.movie_id = ?
         ORDER BY s.start_time ASC"
    );
    mysqli_stmt_bind_param($stmt, "i", $movie_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $showtimes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $showtimes[] = $row;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $showtimes;
}
?>
