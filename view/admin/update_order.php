<?php
include '../config/db.php';
ob_start();

$id = $_GET['id'];

// Lấy đơn hàng
$order = $conn->query("SELECT * FROM don_hang WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['trang_thai'];

    $conn->query("UPDATE don_hang SET trang_thai = '$status' WHERE id = $id");

    header("Location: orders.php");
    exit;
}
?>

<h2 class="mb-4">Cập nhật đơn hàng #<?= $order['id'] ?></h2>

<form method="POST" class="col-md-4">

    <label class="mb-2">Trạng thái đơn hàng</label>
    <select class="form-select" name="trang_thai" required>
        <option value="Chờ xử lý" <?= ($order['trang_thai']=="Chờ xử lý"?'selected':'') ?>>Chờ xử lý</option>
        <option value="Đang giao" <?= ($order['trang_thai']=="Đang giao"?'selected':'') ?>>Đang giao</option>
        <option value="Hoàn thành" <?= ($order['trang_thai']=="Hoàn thành"?'selected':'') ?>>Hoàn thành</option>
        <option value="Đã hủy" <?= ($order['trang_thai']=="Đã hủy"?'selected':'') ?>>Đã hủy</option>
    </select>

    <button class="btn btn-primary mt-3" type="submit">Cập nhật</button>

</form>

<a href="orders.php" class="btn btn-secondary mt-3">← Quay lại</a>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>
