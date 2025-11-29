<?php
// Bắt đầu session để kiểm tra vai trò nếu cần
if (session_status() == PHP_SESSION_NONE) session_start();
include __DIR__ . '/../../config/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Nếu có id thì hiển thị chi tiết
// Nếu admin truy cập mà không truyền role, mặc định hiển thị tất cả
$role = isset($_GET['role']) ? intval($_GET['role']) : (isset($_SESSION['user']) && isset($_SESSION['user']['vai_tro']) && $_SESSION['user']['vai_tro'] == 1 ? -1 : 0);

if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, ho_ten, email, so_dien_thoai, dia_chi, vai_tro, ngay_tao FROM nguoi_dung WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_row = $result->fetch_assoc();
    if (!$user_row) {
        $error_msg = "Không tìm thấy người dùng với ID $id";
    }
}

ob_start();
?>

<?php if (isset($user_row) && $user_row): ?>
    <h2>Thông tin người dùng #<?php echo $user_row['id']; ?></h2>

    <div class="card p-3">
        <div class="row mb-2">
            <div class="col-md-3"><strong>Họ và tên:</strong></div>
            <div class="col-md-9"><?php echo htmlspecialchars($user_row['ho_ten']); ?></div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3"><strong>Email:</strong></div>
            <div class="col-md-9"><?php echo htmlspecialchars($user_row['email']); ?></div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3"><strong>Số điện thoại:</strong></div>
            <div class="col-md-9"><?php echo htmlspecialchars($user_row['so_dien_thoai']); ?></div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3"><strong>Địa chỉ:</strong></div>
            <div class="col-md-9"><?php echo htmlspecialchars($user_row['dia_chi']); ?></div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3"><strong>Vai trò:</strong></div>
            <div class="col-md-9"><?php echo ($user_row['vai_tro'] == 1) ? 'Admin' : 'Khách'; ?></div>
        </div>
        <div class="row mb-2">
            <div class="col-md-3"><strong>Ngày tạo:</strong></div>
            <div class="col-md-9"><?php echo $user_row['ngay_tao']; ?></div>
        </div>

        <a href="users.php?role=<?php echo $role; ?>" class="btn btn-secondary mt-3">Quay lại danh sách</a>

        <!-- Liệt kê đơn hàng của người dùng (nếu có) -->
        <div class="mt-4">
            <h5>Đơn hàng của khách hàng</h5>
            <?php
            $stmt2 = $conn->prepare('SELECT id, tong_tien, trang_thai, ngay_dat FROM don_hang WHERE nguoi_dung_id = ? ORDER BY ngay_dat DESC');
            $stmt2->bind_param('i', $id);
            $stmt2->execute();
            $orders = $stmt2->get_result();
            if ($orders && $orders->num_rows > 0):
            ?>
                <table class="table table-sm table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo number_format($order['tong_tien']); ?> đ</td>
                                <td><?php echo htmlspecialchars($order['trang_thai']); ?></td>
                                <td><?php echo $order['ngay_dat']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-muted">Chưa có đơn hàng nào.</div>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>

    <h2>Danh sách người dùng</h2>

    <?php
    // Liệt kê cơ bản - thêm phân trang đơn giản
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    // bộ lọc vai trò: -1 = tất cả, 0 = Khách, 1 = Admin
    $role = isset($_GET['role']) ? intval($_GET['role']) : 0;
    $sql_count = "SELECT COUNT(*) as total FROM nguoi_dung WHERE 1";
    if ($search !== '') {
        $search_safe = $conn->real_escape_string($search);
        $sql_count .= " AND (ho_ten LIKE '%$search_safe%' OR email LIKE '%$search_safe%')";
    }
    if ($role !== -1) {
        $sql_count .= " AND vai_tro = " . intval($role);
    }
    $total_row = $conn->query($sql_count)->fetch_assoc()['total'];
    $total_page = max(1, ceil($total_row / $limit));

    $sql = "SELECT id, ho_ten, email, so_dien_thoai, dia_chi, vai_tro, ngay_tao FROM nguoi_dung WHERE 1";
    if ($search !== '') {
        $sql .= " AND (ho_ten LIKE '%$search_safe%' OR email LIKE '%$search_safe%')";
    }
    if ($role !== -1) {
        $sql .= " AND vai_tro = " . intval($role);
    }

    $sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    if (!$result) {
        $error_msg = 'Lỗi truy vấn danh sách người dùng: ' . $conn->error;
    }
    ?>

    <form method="GET" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên hoặc email..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="role" class="form-select me-2" style="max-width:160px;">
            <option value="0" <?php echo ($role === 0) ? 'selected' : ''; ?>>Khách</option>
            <option value="1" <?php echo ($role === 1) ? 'selected' : ''; ?>>Admin</option>
            <option value="-1" <?php echo ($role === -1) ? 'selected' : ''; ?>>Tất cả</option>
        </select>
        <button class="btn btn-primary">Tìm</button>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ & tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Địa chỉ</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($error_msg)): ?>
                <tr><td colspan="8" class="text-center text-danger"><?php echo htmlspecialchars($error_msg); ?></td></tr>
            <?php elseif ($result->num_rows === 0): ?>
                <tr><td colspan="8" class="text-center">Không có người dùng phù hợp.</td></tr>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['ho_ten']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['so_dien_thoai']); ?></td>
                    <td><?php echo htmlspecialchars($row['dia_chi']); ?></td>
                    <td><?php echo ($row['vai_tro'] == 1) ? 'Admin' : 'Khách'; ?></td>
                    <td><?php echo $row['ngay_tao']; ?></td>
                    <td>
                        <a href="users.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Xem</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

    
    <?php if ($total_page > 1) : ?>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?search=<?php echo urlencode($search); ?>&role=<?php echo $role; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>
