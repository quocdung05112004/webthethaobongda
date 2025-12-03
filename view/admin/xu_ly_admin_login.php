<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quanlydothethao");

$email = $_POST['email'];
$mat_khau = $_POST['mat_khau'];

$sql = "SELECT * FROM nguoi_dung WHERE email = '$email' AND vai_tro = 1";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $admin = $result->fetch_assoc();

    if ($mat_khau == $admin['mat_khau']) {
        $_SESSION['admin'] = $admin;
        header("Location: home.php");
    } else {
        $_SESSION['error'] = "Sai mật khẩu";
        header("Location: admin_login.php");
    }
} else {
    $_SESSION['error'] = "Không tồn tại admin";
    header("Location: admin_login.php");
}
