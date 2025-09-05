<?php
require_once __DIR__ . '/../functions/movie_functions.php';
require_once __DIR__ . '/../functions/showtime_functions.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID phim");
}

$movieId = intval($_GET['id']);
$movie = getMovieById($movieId);

if (!$movie) {
    die("Phim không tồn tại");
}

$showtimes = getShowtimesByMovie($movieId);
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($movie['title']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

  <h2 class="mb-3"><?= htmlspecialchars($movie['title']) ?></h2>
  <div class="row mb-4">
    <div class="col-md-4">
      <img src="<?= htmlspecialchars($movie['poster_url']) ?>" class="img-fluid rounded shadow" alt="Poster">
    </div>
    <div class="col-md-8">
      <p><strong>Thời lượng:</strong> <?= $movie['duration_min'] ?> phút</p>
      <p><strong>Ngày phát hành:</strong> <?= $movie['release_date'] ?></p>
      <p><strong>Phân loại:</strong> <?= $movie['rating'] ?></p>
      <p><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
    </div>
  </div>

  <h4>🎟 Suất chiếu</h4>
  <?php if (empty($showtimes)): ?>
    <p>Chưa có suất chiếu nào.</p>
  <?php else: ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Ngày giờ</th>
          <th>Rạp</th>
          <th>Phòng</th>
          <th>Giá vé</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($showtimes as $s): ?>
          <tr>
            <td><?= $s['start_time'] ?></td>
            <td><?= htmlspecialchars($s['theater_name']) ?></td>
            <td><?= htmlspecialchars($s['room_name']) ?></td>
            <td><?= number_format($s['price']) ?> VND</td>
            <td>
              <a href="showtime.php?id=<?= $s['id'] ?>" class="btn btn-success btn-sm">Đặt vé</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</body>
</html>
