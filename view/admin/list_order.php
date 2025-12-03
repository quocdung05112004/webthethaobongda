<?php
include '../config/db.php';
ob_start();

// Lấy danh sách đơn hàng
$sql = "SELECT dh.*, nd.ho_ten 
        FROM don_hang dh 
        JOIN nguoi_dung nd ON dh.nguoi_dung_id = nd.id
        ORDER BY dh.id DESC";

$orders = $conn->query($sql);
?>

<h2 class="mb-4">Quản lý đơn hàng</h2>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th width="160">Hành động</th>
        </tr>
    </thead>

    <tbody>
        <?php while ($row = $orders->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['ho_ten'] ?></td>
            <td><?= number_format($row['tong_tien']) ?> đ</td>
            <td><span class="badge bg-info"><?= $row['trang_thai'] ?></span></td>
            <td><?= $row['ngay_dat'] ?></td>
            <td>
                <a href="order_view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Chi tiết</a>
                <a href="order_update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Cập nhật</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>
