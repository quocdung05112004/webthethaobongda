<?php
session_start();
include '../../config/db.php';

$nguoi_dung_id = $_SESSION['user']['id'];

$sql = "SELECT * FROM don_hang WHERE nguoi_dung_id = $nguoi_dung_id ORDER BY id DESC";
$result = $conn->query($sql);
?>

<h3>Đơn hàng của tôi</h3>

<table class="table table-bordered">
    <tr>
        <th>Mã đơn</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
        <th>Ngày đặt</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= number_format($row['tong_tien']) ?> đ</td>
            <td><?= $row['trang_thai'] ?></td>
            <td><?= $row['ngay_dat'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>