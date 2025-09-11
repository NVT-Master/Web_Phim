<?php
require_once __DIR__ . '/../functions/showtime_functions.php';
require_once __DIR__ . '/../functions/seat_functions.php';
require_once __DIR__ . '/../functions/movie_functions.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID suất chiếu");
}

$showtimeId = intval($_GET['id']);
$conn = getDbConnection();
$stmt = $conn->prepare("SELECT s.*, m.title, r.id as room_id, r.name as room_name, t.name as theater_name
                        FROM showtimes s
                        JOIN movies m ON s.movie_id = m.id
                        JOIN rooms r ON s.room_id = r.id
                        JOIN theaters t ON r.theater_id = t.id
                        WHERE s.id = ?");
$stmt->bind_param("i", $showtimeId);
$stmt->execute();
$result = $stmt->get_result();
$showtime = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$showtime) {
    die("Suất chiếu không tồn tại");
}

$seats = getSeatsByRoom($showtime['room_id']);
$bookedSeats = getBookedSeats($showtimeId);
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Chọn ghế - <?= htmlspecialchars($showtime['title']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .seat {
      width: 40px; height: 40px;
      margin: 3px; text-align: center;
      line-height: 40px;
      border-radius: 5px;
      cursor: pointer;
    }
    .seat.available { background: #e0e0e0; }
    .seat.booked { background: #ff6666; cursor: not-allowed; }
    .seat.selected { background: #66cc66; }
  </style>
</head>
<body class="container py-4">

  <h3>🎬 <?= htmlspecialchars($showtime['title']) ?></h3>
  <p><strong>Rạp:</strong> <?= htmlspecialchars($showtime['theater_name']) ?> - <?= htmlspecialchars($showtime['room_name']) ?></p>
  <p><strong>Thời gian:</strong> <?= $showtime['start_time'] ?></p>
  <p><strong>Giá vé:</strong> <?= number_format($showtime['price']) ?> VND</p>

  <h4 class="mt-4">Chọn ghế</h4>

  <form method="post" action="book.php">
    <input type="hidden" name="showtime_id" value="<?= $showtimeId ?>">
    <div class="d-flex flex-column">
      <?php
      $rows = [];
      foreach ($seats as $s) {
          $rows[$s['row_label']][] = $s;
      }

      foreach ($rows as $rowLabel => $rowSeats): ?>
        <div class="d-flex align-items-center mb-2">
          <span class="me-2"><strong><?= $rowLabel ?></strong></span>
          <?php foreach ($rowSeats as $seat): 
              $class = "seat available";
              $disabled = "";
              if (in_array($seat['id'], $bookedSeats)) {
                  $class = "seat booked";
                  $disabled = "disabled";
              }
          ?>
            <label>
              <input type="checkbox" name="seats[]" value="<?= $seat['id'] ?>" class="d-none" <?= $disabled ?>>
              <div class="<?= $class ?>"><?= $seat['seat_number'] ?></div>
            </label>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Giữ ghế & Tiếp tục</button>
  </form>

  <script>
    document.querySelectorAll('label input[type=checkbox]').forEach(chk => {
      chk.addEventListener('change', function() {
        const box = this.parentElement.querySelector('.seat');
        if (this.checked) {
          box.classList.add('selected');
        } else {
          box.classList.remove('selected');
        }
      });
    });
  </script>

</body>
</html>
