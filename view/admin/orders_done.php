<?php
ob_start(); // bắt đầu buffer
include '../../config/db.php';

$search = $_GET['search'] ?? '';

// Query đơn hàng đã duyệt
$sql = "SELECT don_hang.*, nguoi_dung.ho_ten 
        FROM don_hang 
        JOIN nguoi_dung ON don_hang.nguoi_dung_id = nguoi_dung.id
        WHERE trang_thai = 'Hoàn thành'
        AND (don_hang.id LIKE '%$search%' OR nguoi_dung.ho_ten LIKE '%$search%')
        ORDER BY don_hang.id DESC";
$result = $conn->query($sql);
?>

<div class="container-fluid">
    <h3 class="mb-4">Lịch sử đơn hàng đã duyệt</h3>

    <!-- Form tìm kiếm -->
    <form class="mb-4 row g-2">
        <div class="col-md-4">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                placeholder="Tìm mã đơn hoặc tên khách..." class="form-control">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary"><i class="bi bi-search"></i> Tìm kiếm</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['ho_ten']) ?></td>
                            <td><?= number_format($row['tong_tien']) ?> đ</td>
                            <td><?= $row['ngay_dat'] ?></td>
                            <td><span class="badge bg-success"><i class="bi bi-check-circle"></i> Hoàn thành</span></td>
                            <td>
                                <a href="order_detail.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Không có đơn hàng đã duyệt</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>