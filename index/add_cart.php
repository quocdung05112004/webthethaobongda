<?php
session_start();
include '../config/db.php';

$product_id = $_POST['product_id'];
$so_luong = isset($_POST['so_luong']) ? intval($_POST['so_luong']) : 1;

if($so_luong < 1) $so_luong = 1;

if(isset($_SESSION['user'])){
    $user_id = $_SESSION['user']['id'];
    $res = mysqli_query($conn, "SELECT * FROM gio_hang WHERE nguoi_dung_id=$user_id AND san_pham_id=$product_id");
    if(mysqli_num_rows($res)>0){
        mysqli_query($conn, "UPDATE gio_hang SET so_luong = so_luong + $so_luong WHERE nguoi_dung_id=$user_id AND san_pham_id=$product_id");
    } else {
        mysqli_query($conn, "INSERT INTO gio_hang (nguoi_dung_id, san_pham_id, so_luong) VALUES ($user_id, $product_id, $so_luong)");
    }
} else {
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $found = false;
    foreach($_SESSION['cart'] as &$item){
        if($item['id'] == $product_id){
            $item['so_luong'] += $so_luong;
            $found = true;
            break;
        }
    }
    if(!$found){
        $res = mysqli_query($conn, "SELECT * FROM san_pham WHERE id=$product_id");
        $row = mysqli_fetch_assoc($res);
        $_SESSION['cart'][] = [
            'id' => $row['id'],
            'ten' => $row['ten'],
            'gia' => $row['gia'],
            'hinh_anh' => $row['hinh_anh'],
            'so_luong' => $so_luong
        ];
    }
}

echo 'ok';
