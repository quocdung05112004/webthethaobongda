<?php
$host = "localhost: 3307";
$user = "root";
$pass = "";
$dbname = "web_ban_do_the_thao";

$conn = new mysqli($host, $user, $pass, $dbname);
mysqli_set_charset($conn, "utf8");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
