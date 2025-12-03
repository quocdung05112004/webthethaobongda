<?php
session_start();
include '../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $cartId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM gio_hang WHERE id=? AND nguoi_dung_id=?");
    $stmt->bind_param('ii', $cartId, $_SESSION['user']['id']);
    $stmt->execute();
    header('Location: cart.php');
    exit;
}

// Xử lý xóa toàn bộ giỏ hàng
if (isset($_GET['clear'])) {
    $stmt = $conn->prepare("DELETE FROM gio_hang WHERE nguoi_dung_id=?");
    $stmt->bind_param('i', $_SESSION['user']['id']);
    $stmt->execute();
    header('Location: cart.php');
    exit;
}

// Xử lý tăng giảm số lượng
if (isset($_GET['update']) && isset($_GET['qty'])) {
    $cartId = intval($_GET['update']);
    $qty = max(1, intval($_GET['qty'])); // tối thiểu 1
    $stmt = $conn->prepare("UPDATE gio_hang SET so_luong=? WHERE id=? AND nguoi_dung_id=?");
    $stmt->bind_param('iii', $qty, $cartId, $_SESSION['user']['id']);
    $stmt->execute();
    header('Location: cart.php');
    exit;
}

// Lấy giỏ hàng từ DB
$cart = [];
$total = 0;
$stmt = $conn->prepare("
    SELECT g.id AS gio_hang_id, g.so_luong, s.id AS san_pham_id, s.ten, s.gia, s.hinh_anh
    FROM gio_hang g
    JOIN san_pham s ON g.san_pham_id = s.id
    WHERE g.nguoi_dung_id=?
");
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $cart[] = $row;
    $total += $row['gia'] * $row['so_luong'];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - SPORTSHOP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <h3>Giỏ hàng của bạn</h3>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tạm tính</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cart)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">Giỏ hàng trống</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cart as $item): ?>
                            <tr>
                                <td class="d-flex align-items-center gap-2">
                                    <img src="../asset/upload/<?= htmlspecialchars($item['hinh_anh']) ?>" style="width:60px; height:60px; object-fit:cover;">
                                    <div><?= htmlspecialchars($item['ten']) ?></div>
                                </td>
                                <td><?= number_format($item['gia']) ?> đ</td>
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        <a href="?update=<?= $item['gio_hang_id'] ?>&qty=<?= $item['so_luong'] - 1 ?>" class="btn btn-outline-secondary btn-sm <?= $item['so_luong'] <= 1 ? 'disabled' : '' ?>">-</a>
                                        <span><?= $item['so_luong'] ?></span>
                                        <a href="?update=<?= $item['gio_hang_id'] ?>&qty=<?= $item['so_luong'] + 1 ?>" class="btn btn-outline-secondary btn-sm">+</a>
                                    </div>
                                </td>
                                <td><?= number_format($item['gia'] * $item['so_luong']) ?> đ</td>
                                <td>
                                    <a href="?delete=<?= $item['gio_hang_id'] ?>" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="?clear=1" class="btn btn-outline-danger <?= empty($cart) ? 'disabled' : '' ?>">Xóa toàn bộ</a>
            <div class="cart-total">Tổng: <strong><?= number_format($total) ?> đ</strong></div>
        </div>

        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-secondary flex-grow-1"><i class="bi bi-arrow-left"></i> Tiếp tục mua sắm</a>
            <a href="checkout.php" class="btn btn-primary flex-grow-1 <?= empty($cart) ? 'disabled' : '' ?>"><i class="bi bi-bag-check"></i> Thanh toán</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>