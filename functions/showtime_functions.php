<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy danh sách suất chiếu theo ID phim
 */
function getShowtimesByMovie($movieId)
{
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM showtimes WHERE movie_id = ? ORDER BY start_time ASC");
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    $showtimes = [];
    while ($row = $result->fetch_assoc()) {
        $showtimes[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $showtimes;
}
