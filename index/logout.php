<?php
// Bắt đầu session nếu chưa bắt đầu
if (session_status() == PHP_SESSION_NONE) session_start();
// Hủy session và quay về trang đăng nhập
session_unset();
session_destroy();
header('Location: index.php');
exit;
?>
