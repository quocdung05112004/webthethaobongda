<?php
include __DIR__ . '/../../config/db.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Trang hiện tại
$limit = 6; // 6 sản phẩm/trang
$offset = ($page - 1) * $limit;

// Lấy danh sách danh mục để tạo dropdown
$categories = $conn->query("SELECT * FROM danh_muc ORDER BY ten ASC");

// SQL đếm tổng sản phẩm để tính số trang
$sql_count = "SELECT COUNT(*) as total FROM san_pham WHERE 1";
if ($keyword != "") {
    $keyword_safe = $conn->real_escape_string($keyword);
    $sql_count .= " AND ten LIKE '%$keyword_safe%'";
}
if ($category_id > 0) {
    $sql_count .= " AND danh_muc_id = $category_id";
}
$result_count = $conn->query($sql_count);
$total_row = $result_count->fetch_assoc()['total'];
$total_page = ceil($total_row / $limit);

// SQL lấy sản phẩm trang hiện tại
$sql = "SELECT * FROM san_pham WHERE 1";
if ($keyword != "") {
    $sql .= " AND ten LIKE '%$keyword_safe%'";
}
if ($category_id > 0) {
    $sql .= " AND danh_muc_id = $category_id";
}
$sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

if (!$result) {
    die("SQL ERROR: " . $conn->error);
}

ob_start();
?>

<h2>Quản lý sản phẩm</h2>

<!-- Thanh tìm kiếm + lọc danh mục -->
<form method="GET" class="d-flex mb-3 align-items-center">
    <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm sản phẩm..."
        value="<?php echo htmlspecialchars($keyword); ?>" style="max-width: 200px;">

    <select name="category_id" class="form-select me-2" style="max-width: 200px;">
        <option value="0">-- Chọn danh mục --</option>
        <?php while ($cat = $categories->fetch_assoc()) : ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $category_id) ? 'selected' : ''; ?>>
                <?php echo $cat['ten']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button class="btn btn-primary">Tìm</button>
</form>

<a href="add_SanPham.php" class="btn btn-success mb-3">+ Thêm sản phẩm</a>

<!-- Bảng danh sách sản phẩm -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Ảnh</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['ten']; ?></td>
                <td><?php echo number_format($row['gia']); ?> đ</td>
                <td>
                    <?php if ($row['hinh_anh']) : ?>
                        <img src="../../asset/img/<?php echo $row['hinh_anh']; ?>" width="60">
                    <?php endif; ?>
                </td>
                <td><?php echo substr($row['mo_ta'], 0, 40) . "..."; ?></td>
                <td>
                    <a href="edit_SanPham.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="delete_SanPham.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Bạn chắc chắn muốn xóa?');"
                        class="btn btn-danger btn-sm">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Phân trang -->
<?php if ($total_page > 1) : ?>
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?keyword=<?php echo urlencode($keyword); ?>&category_id=<?php echo $category_id; ?>&page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>