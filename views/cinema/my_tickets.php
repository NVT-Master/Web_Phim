<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';

$userId = $_SESSION['user_id'] ?? 1;

$conn = getDbConnection();
$sql = "SELECT b.id, b.status, b.total_amount, b.created_at, m.title, s.start_time 
        FROM bookings b
        JOIN showtimes s ON b.showtime_id = s.id
        JOIN movies m ON s.movie_id = m.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}
$stmt->close();
$conn->close();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Vé của tôi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>🎟 Vé của tôi</h2>
  <?php if (empty($tickets)): ?>
    <p>Chưa có vé nào.</p>
  <?php else: ?>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Mã vé</th>
          <th>Phim</th>
          <th>Suất chiếu</th>
          <th>Tổng tiền</th>
          <th>Trạng thái</th>
          <th>Ngày đặt</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tickets as $t): ?>
          <tr>
            <td>#<?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['title']) ?></td>
            <td><?= $t['start_time'] ?></td>
            <td><?= number_format($t['total_amount']) ?> VND</td>
            <td><?= $t['status'] ?></td>
            <td><?= $t['created_at'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>
