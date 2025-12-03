<?php
ob_start();
include '../../config/db.php';

// Lấy id đơn hàng, đảm bảo là số
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin đơn hàng và khách hàng
$sql_order = "SELECT don_hang.*, nguoi_dung.ho_ten, nguoi_dung.email 
              FROM don_hang 
              JOIN nguoi_dung ON don_hang.nguoi_dung_id = nguoi_dung.id
              WHERE don_hang.id = $id";
$order_result = $conn->query($sql_order);

if ($order_result->num_rows == 0) {
    echo "<div class='container'><div class='alert alert-danger'>Đơn hàng không tồn tại!</div></div>";
    exit;
}

$order = $order_result->fetch_assoc();

// Lấy chi tiết sản phẩm của đơn hàng
$sql_items = "SELECT dhct.*, sp.ten, sp.hinh_anh 
              FROM don_hang_chi_tiet dhct
              JOIN san_pham sp ON dhct.san_pham_id = sp.id
              WHERE dhct.don_hang_id = $id";
$items_result = $conn->query($sql_items);
?>

<div class="container-fluid">
    <h3 class="mb-4">Chi tiết đơn hàng #<?= $order['id'] ?></h3>

    <div class="row mb-3">
        <div class="col-md-6">
            <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['ho_ten']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
        </div>
        <div class="col-md-6">
            <p><strong>Trạng thái:</strong>
                <?php if ($order['trang_thai'] == 'Chờ xử lý'): ?>
                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                <?php else: ?>
                    <span class="badge bg-success">Hoàn thành</span>
                <?php endif; ?>
            </p>
            <p><strong>Ngày đặt:</strong> <?= $order['ngay_dat'] ?></p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items_result->num_rows > 0): ?>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['ten']) ?></td>
                            <td>
                                <?php if ($item['hinh_anh']): ?>
                                    <img src="../../asset/upload/<?= $item['hinh_anh'] ?>" alt="<?= htmlspecialchars($item['ten']) ?>" width="50">
                                <?php else: ?>
                                    <span class="text-muted">Chưa có</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['so_luong'] ?></td>
                            <td><?= number_format($item['gia']) ?> đ</td>
                            <td><?= number_format($item['thanh_tien']) ?> đ</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Đơn hàng chưa có sản phẩm</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p class="text-end fw-bold">Tổng tiền: <?= number_format($order['tong_tien']) ?> đ</p>
    <a href="orders.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>