<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

// Lấy giỏ hàng hiện tại
$stmt = $conn->prepare("
    SELECT g.san_pham_id, g.so_luong, p.gia
    FROM gio_hang g
    JOIN san_pham p ON g.san_pham_id = p.id
    WHERE g.nguoi_dung_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    // Giỏ hàng trống, về trang giỏ hàng
    header("Location: cart.php");
    exit;
}

$totalAmount = 0;
$cartItems = [];

while ($row = $res->fetch_assoc()) {
    $thanhTien = $row['gia'] * $row['so_luong'];
    $totalAmount += $thanhTien;
    $cartItems[] = [
        'san_pham_id' => $row['san_pham_id'],
        'so_luong' => $row['so_luong'],
        'gia' => $row['gia'],
        'thanh_tien' => $thanhTien
    ];
}

// Tạo đơn hàng mới trạng thái "Chờ xét duyệt"
$status = 'Chờ xét duyệt';
$stmtOrder = $conn->prepare("INSERT INTO don_hang (nguoi_dung_id, tong_tien, trang_thai, ngay_dat) VALUES (?, ?, ?, NOW())");
$stmtOrder->bind_param("ids", $userId, $totalAmount, $status);
$stmtOrder->execute();
$orderId = $stmtOrder->insert_id;

// Thêm chi tiết đơn
$stmtDetail = $conn->prepare("INSERT INTO don_hang_chi_tiet (don_hang_id, san_pham_id, so_luong, gia, thanh_tien) VALUES (?, ?, ?, ?, ?)");
foreach ($cartItems as $item) {
    $stmtDetail->bind_param("iiidd", $orderId, $item['san_pham_id'], $item['so_luong'], $item['gia'], $item['thanh_tien']);
    $stmtDetail->execute();
}

// Xóa giỏ hàng sau khi tạo đơn
$conn->query("DELETE FROM gio_hang WHERE nguoi_dung_id=$userId");

// Quay về trang đơn hàng
header("Location: don_hang.php");
exit;
