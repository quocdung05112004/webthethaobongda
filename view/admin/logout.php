<?php
session_start();
session_unset();
session_destroy();
// Quay về trang đăng nhập chính
header('Location: admin_login.php');
exit;
