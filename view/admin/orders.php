<?php
ob_start();

include '../../config/db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT don_hang.*, nguoi_dung.ho_ten 
        FROM don_hang 
        JOIN nguoi_dung ON don_hang.nguoi_dung_id = nguoi_dung.id
        WHERE don_hang.id LIKE '%$search%' 
        OR nguoi_dung.ho_ten LIKE '%$search%'
        ORDER BY don_hang.id DESC";

$result = $conn->query($sql);
?>

<div class="container-fluid">
    <h3 class="mb-4">Quản lý đơn hàng</h3>

    <form class="mb-4 row g-2">
        <div class="col-auto">
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
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
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
                            <td>
                                <?php
                                if ($row['trang_thai'] == 'Chờ xử lý') {
                                    echo "<span class='badge bg-warning text-dark'>Chờ xử lý</span>";
                                } elseif ($row['trang_thai'] == 'Hoàn thành') {
                                    echo "<span class='badge bg-success'>Hoàn thành</span>";
                                } else {
                                    echo "<span class='badge bg-danger'>Từ chối</span>";
                                }
                                ?>
                            </td>
                            <td><?= $row['ngay_dat'] ?></td>
                            <td class="d-flex justify-content-center gap-1">
                                <?php if ($row['trang_thai'] == 'Chờ xử lý'): ?>
                                    <a href="duyet_don.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm"
                                        title="Duyệt đơn">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                    <a href="tu_choi_don.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                        title="Từ chối đơn">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="order_detail.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Không có đơn hàng</td>
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