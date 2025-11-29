<?php
include __DIR__ . '/../../config/db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM san_pham WHERE id = $id");

header("Location: quanly_SanPham.php");
exit;
