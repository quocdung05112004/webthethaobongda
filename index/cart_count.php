<?php
session_start();
$count = 0;

if(isset($_SESSION['user'])){
    include '../config/db.php';
    $user_id = $_SESSION['user']['id'];
    $res = mysqli_query($conn,"SELECT COUNT(*) AS total FROM gio_hang WHERE nguoi_dung_id=$user_id");
    $row = mysqli_fetch_assoc($res);
    $count = $row['total'] ?? 0;
} else {
    if(isset($_SESSION['cart'])){
        $count = count($_SESSION['cart']); // mỗi sản phẩm khác nhau +1
    }
}

echo $count;
