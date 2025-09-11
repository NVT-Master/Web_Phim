<?php
require_once __DIR__ . '/../functions/db_connection.php';

$conn = getDbConnection();

// Cập nhật tất cả booking PENDING đã hết hạn => EXPIRED
$sql = "UPDATE bookings 
        SET status = 'EXPIRED' 
        WHERE status = 'PENDING' 
        AND hold_expires_at < NOW()";

if ($conn->query($sql)) {
    echo "✅ Đã cập nhật vé hết hạn.";
} else {
    echo "❌ Lỗi: " . $conn->error;
}

$conn->close();
