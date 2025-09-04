<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang quản trị - Đặt vé xem phim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            height: 100vh;
            background: #343a40;
            color: #fff;
            padding: 20px;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: #ccc;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            border-radius: 6px;
        }

        .sidebar a:hover {
            background: #495057;
            color: #fff;
        }

        .content {
            padding: 20px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <a href="movies.php">🎬 Quản lý phim</a>
            <a href="cinemas.php">🏢 Quản lý rạp</a>
            <a href="seats.php">🪑 Quản lý ghế</a>
            <a href="shows.php">📅 Quản lý suất chiếu</a>
            <a href="users.php">👤 Quản lý người dùng</a>
            <a href="../auth/logout.php">🚪 Đăng xuất</a>
        </div>


        <!-- Content -->
        <div class="content flex-grow-1">
            <h1 class="mb-4">Chào mừng, <?= htmlspecialchars($_SESSION['name']); ?> 👋</h1>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card text-center p-3">
                        <h5>Phim</h5>
                        <p class="display-6">12</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3">
                        <h5>Rạp</h5>
                        <p class="display-6">5</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3">
                        <h5>Vé bán</h5>
                        <p class="display-6">250</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center p-3">
                        <h5>Người dùng</h5>
                        <p class="display-6">80</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>