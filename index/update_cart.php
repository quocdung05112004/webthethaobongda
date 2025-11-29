<?php
session_start();
include '../config/db.php';

$key = $_POST['key'];
$action = $_POST['action'];

if(isset($_SESSION['user'])){
    $user_id = $_SESSION['user']['id'];
    // Lấy cart_id từ key
    $res = mysqli_query($conn,"SELECT id, so_luong FROM gio_hang WHERE id=$key AND nguoi_dung_id=$user_id");
    if($row=mysqli_fetch_assoc($res)){
        $qty = $row['so_luong'];
        if($action=='plus') $qty++;
        elseif($action=='minus' && $qty>1) $qty--;
        mysqli_query($conn,"UPDATE gio_hang SET so_luong=$qty WHERE id=$key");
    }
} else {
    if(isset($_SESSION['cart'][$key])){
        if($action=='plus') $_SESSION['cart'][$key]['so_luong']++;
        elseif($action=='minus' && $_SESSION['cart'][$key]['so_luong']>1) $_SESSION['cart'][$key]['so_luong']--;
    }
}

echo 'ok';
