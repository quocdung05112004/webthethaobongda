<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quanlydothethao";

$conn = new mysqli($host, $user, $pass, $dbname);
mysqli_set_charset($conn, "utf8");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
