<?php
session_start();
require_once __DIR__ . '/functions/db_connection.php';

$conn = getDbConnection();

// Xử lý tìm kiếm
$search = "";
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM movies WHERE title LIKE ?");
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("s", $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM movies");
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt Vé Xem Phim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #141414;
            color: #fff;
        }

        .navbar {
            background-color: #000;
        }

        .navbar-brand {
            font-weight: bold;
            color: #f39c12 !important;
        }

        .movie-card {
            background-color: #1f1f1f;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
        }

        .movie-card:hover {
            transform: scale(1.05);
        }

        .movie-card img {
            height: 250px;
            object-fit: cover;
        }

        footer {
            margin-top: 50px;
            padding: 20px;
            text-align: center;
            background-color: #000;
            color: #aaa;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold text-warning" href="index.php">🎬 MovieBooking</a>

            <!-- Toggle khi màn hình nhỏ -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nội dung Navbar -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Form tìm kiếm -->
                <form class="d-flex ms-auto me-3" method="GET" action="index.php">
                    <input class="form-control me-2" type="search" name="search" placeholder="Tìm phim..."
                        value="<?= htmlspecialchars($search ?? '') ?>">
                    <button class="btn btn-outline-warning" type="submit">🔍</button>
                </form>

                <!-- Menu tài khoản -->
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'ADMIN'): ?>
                            <li class="nav-item"><a class="nav-link text-white" href="views/admin/index.php">Quản lý</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link text-white" href="handle/logout_process.php">Đăng xuất</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link text-white" href="views/auth/login.php">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="views/    auth/register.php">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner -->
    <div id="carouselExampleIndicators" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active position-relative">
                <img src="images/banner.jpg" class="d-block w-100" style="height:550px; object-fit:cover;"
                    alt="Banner">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.5);"></div>
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="text-white">Chào mừng đến MovieBooking 🎬</h2>
                    <p class="text-white">Đặt vé xem phim nhanh chóng và tiện lợi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách phim -->
    <div class="container">
        <h2 class="mb-4">📽️ Danh sách phim</h2>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card movie-card">
                        <img src="images/posters/<?= $row['id'] ?>.jpg" class="card-img-top"
                            alt="<?= htmlspecialchars($row['title']) ?>">
                        <div class="card-body bg-dark bg-opacity-75 text-white">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="card-text">Thể loại: <?= htmlspecialchars($row['genre']) ?></p>
                            <p class="card-text">Thời lượng: <?= htmlspecialchars($row['duration']) ?> phút</p>
                            <a href="views/cinema/book.php?movie_id=<?= $row['id'] ?>" class="btn btn-warning">Đặt vé</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        Copyright © <?= date("Y") ?> MovieBooking. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
