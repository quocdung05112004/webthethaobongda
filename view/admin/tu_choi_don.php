<?php
include '../../config/db.php';

// Lấy id đơn hàng từ URL
$id = $_GET['id'];

// Cập nhật trạng thái thành "Từ chối"
$conn->query("UPDATE don_hang SET trang_thai = 'Từ chối' WHERE id = $id");

// Quay về trang danh sách đơn hàng
header("Location: orders.php");
exit;