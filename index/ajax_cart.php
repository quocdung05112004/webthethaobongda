<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'count' => 0]);
    exit;
}

$userId = $_SESSION['user']['id'];
$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $productId = intval($_POST['product_id']);
    // kiểm tra đã có sp chưa
    $stmt = $conn->prepare("SELECT id, so_luong FROM gio_hang WHERE nguoi_dung_id=? AND san_pham_id=?");
    $stmt->bind_param('ii', $userId, $productId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $qty = $row['so_luong'] + 1;
        $stmt2 = $conn->prepare("UPDATE gio_hang SET so_luong=? WHERE id=?");
        $stmt2->bind_param('ii', $qty, $row['id']);
        $stmt2->execute();
    } else {
        $stmt2 = $conn->prepare("INSERT INTO gio_hang (nguoi_dung_id, san_pham_id, so_luong) VALUES (?, ?, 1)");
        $stmt2->bind_param('ii', $userId, $productId);
        $stmt2->execute();
    }
    // trả về số lượng mới
    $res2 = $conn->query("SELECT SUM(so_luong) as cnt FROM gio_hang WHERE nguoi_dung_id=$userId");
    $row2 = $res2->fetch_assoc();
    echo json_encode(['success' => true, 'count' => intval($row2['cnt'])]);
    exit;
}

if ($action === 'count') {
    $res = $conn->query("SELECT SUM(so_luong) as cnt FROM gio_hang WHERE nguoi_dung_id=$userId");
    $row = $res->fetch_assoc();
    echo json_encode(['count' => intval($row['cnt'])]);
    exit;
}
