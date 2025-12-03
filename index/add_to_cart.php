<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 0, 'msg' => 'Bạn cần đăng nhập']);
    exit;
}

$userId = $_SESSION['user']['id'];
$prodId = intval($_POST['id']);
$qty = intval($_POST['qty']) ?: 1;

// Kiểm tra sản phẩm đã có trong giỏ chưa
$res = $conn->query("SELECT * FROM gio_hang WHERE nguoi_dung_id=$userId AND san_pham_id=$prodId");
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $newQty = $row['so_luong'] + $qty;
    $conn->query("UPDATE gio_hang SET so_luong=$newQty WHERE id=" . $row['id']);
} else {
    $conn->query("INSERT INTO gio_hang (nguoi_dung_id,san_pham_id,so_luong) VALUES ($userId,$prodId,$qty)");
}

// Lấy số lượng giỏ hàng mới
$res = $conn->query("SELECT SUM(so_luong) as cnt FROM gio_hang WHERE nguoi_dung_id=$userId");
$cnt = $res->fetch_assoc()['cnt'];

echo json_encode(['status' => 1, 'msg' => 'Đã thêm vào giỏ', 'count' => $cnt]);
