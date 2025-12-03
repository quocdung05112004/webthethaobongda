<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

// Lấy giỏ hàng của user từ DB
$stmt = $conn->prepare("
    SELECT g.san_pham_id, g.so_luong, p.ten, p.gia
    FROM gio_hang g
    JOIN san_pham p ON g.san_pham_id = p.id
    WHERE g.nguoi_dung_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();

$cartItems = [];
$total = 0;
while ($row = $res->fetch_assoc()) {
    $row['total'] = $row['gia'] * $row['so_luong'];
    $total += $row['total'];
    $cartItems[] = $row;
}

$cartJson = json_encode($cartItems);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="mb-3">
            <a href="index.php" class="btn btn-secondary">&larr; Trang chủ</a>
        </div>

        <h3 class="mb-4">Đơn hàng của bạn</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>SL</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['ten']) ?></td>
                        <td><?= $item['so_luong'] ?></td>
                        <td><?= number_format($item['total']) ?> đ</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4 class="text-end">Tổng thanh toán: <?= number_format($total) ?> đ</h4>

        <div class="d-flex gap-2 mt-3 justify-content-center">
            <!-- Thanh toán -->
            <form action="checkout_info.php" method="POST" class="w-25">
                <input type="hidden" name="cart_json" value='<?= htmlspecialchars($cartJson, ENT_QUOTES) ?>'>
                <input type="hidden" name="payment_type" value="now">
                <button type="submit" class="btn btn-success w-100">Thanh Toán Ngay</button>
            </form>


            <form action="process_checkout.php" method="POST" class="w-25">
                <input type="hidden" name="cart_json" value='<?= htmlspecialchars($cartJson, ENT_QUOTES) ?>'>
                <input type="hidden" name="payment_type" value="later">
                <button type="submit" class="btn btn-primary w-100">Thanh Toán Sau</button>
            </form>

            <form action="process_checkout.php" method="POST" class="w-25">
                <input type="hidden" name="cart_json" value='<?= htmlspecialchars($cartJson, ENT_QUOTES) ?>'>
                <input type="hidden" name="payment_type" value="cod">
                <button type="submit" class="btn btn-info w-100">Thanh Toán Khi Nhận Hàng</button>
            </form>
        </div>
    </div>
</body>

</html>