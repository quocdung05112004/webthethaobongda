<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId  = $_SESSION['user']['id'];
$orderId = $_POST['order_id'] ?? null;

if (!$orderId) {
    die("Thiếu mã đơn hàng!");
}

// Kiểm tra đơn hàng
$stmt = $conn->prepare("SELECT * FROM don_hang WHERE id=? AND nguoi_dung_id=?");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("Đơn hàng không hợp lệ!");
}

// Cập nhật trạng thái thanh toán
$stmt = $conn->prepare("UPDATE don_hang SET trang_thai='Đã thanh toán' WHERE id=?");
$stmt->bind_param("i", $orderId);
$stmt->execute();

// Redirect về danh sách đơn hàng
header("Location: don_hang.php");
exit;
