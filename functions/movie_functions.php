<?php
require_once __DIR__ . '/db_connection.php';

// Lấy tất cả phim
function getAllMovies() {
    $conn = getDbConnection();
    $sql = "SELECT * FROM movies ORDER BY release_date DESC";
    $result = mysqli_query($conn, $sql);

    $movies = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $movies[] = $row;
        }
    }

    mysqli_close($conn);
    return $movies;
}

// Lấy phim theo ID
function getMovieById($id) {
    $conn = getDbConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $movie = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $movie;
}
