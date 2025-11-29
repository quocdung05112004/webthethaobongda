<?php
session_start();
include '../config/db.php';

$key = $_POST['key'];

if(isset($_SESSION['user'])){
    $user_id = $_SESSION['user']['id'];
    // Lấy cart_id từ key
    $res = mysqli_query($conn,"SELECT id FROM gio_hang WHERE id=$key AND nguoi_dung_id=$user_id");
    if(mysqli_num_rows($res)>0){
        mysqli_query($conn,"DELETE FROM gio_hang WHERE id=$key");
    }
} else {
    if(isset($_SESSION['cart'][$key])){
        unset($_SESSION['cart'][$key]);
    }
}

echo 'ok';
