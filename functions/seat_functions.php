<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy tất cả ghế của một phòng
 */
function getSeatsByRoom($roomId) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM seats WHERE room_id = ? ORDER BY row_label, seat_number ASC");
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $seats = [];
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $seats;
}

/**
 * Lấy danh sách ghế đã được đặt của một suất chiếu
 */
function getBookedSeats($showtimeId) {
    $conn = getDbConnection();

    $sql = "SELECT bi.seat_id 
            FROM booking_items bi
            JOIN bookings b ON bi.booking_id = b.id
            WHERE b.showtime_id = ? 
              AND b.status IN ('PENDING','CONFIRMED')";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $showtimeId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $booked = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $booked[] = $row['seat_id'];
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $booked;
}
?>