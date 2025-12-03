<?php
include '../config/db.php';
ob_start();

$id = $_GET['id'];

// Thông tin đơn hàng
$order = $conn->query("
    SELECT dh.*, nd.ho_ten, nd.email, nd.so_dien_thoai
    FROM don_hang dh
    JOIN nguoi_dung nd ON dh.nguoi_dung_id = nd.id
    WHERE dh.id = $id
")->fetch_assoc();

// Chi tiết đơn hàng
$details = $conn->query("
    SELECT ct.*, sp.ten 
    FROM don_hang_chi_tiet ct
    JOIN san_pham sp ON ct.san_pham_id = sp.id
    WHERE ct.don_hang_id = $id
");
?>

<h2 class="mb-4">Chi tiết đơn hàng #<?= $order['id'] ?></h2>

<h5>Thông tin khách hàng</h5>
<ul class="mb-4">
    <li><b>Họ tên:</b> <?= $order['ho_ten'] ?></li>
    <li><b>Email:</b> <?= $order['email'] ?></li>
    <li><b>SĐT:</b> <?= $order['so_dien_thoai'] ?></li>
</ul>

<h5>Sản phẩm</h5>
<table class="table table-bordered">
    <thead class="table-secondary">
        <tr>
            <th>Sản phẩm</th>
            <th width="80">SL</th>
            <th>Giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($item = $details->fetch_assoc()) { ?>
        <tr>
            <td><?= $item['ten'] ?></td>
            <td><?= $item['so_luong'] ?></td>
            <td><?= number_format($item['gia']) ?> đ</td>
            <td><?= number_format($item['thanh_tien']) ?> đ</td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<h4 class="mt-3">Tổng tiền: 
    <span class="text-danger"><?= number_format($order['tong_tien']) ?> đ</span>
</h4>

<a href="orders.php" class="btn btn-secondary mt-3">← Quay lại</a>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>
