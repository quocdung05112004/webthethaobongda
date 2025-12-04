<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

$sql = "SELECT * FROM don_hang WHERE nguoi_dung_id=? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table thead th {
            background-color: #343a40;
            color: #fff;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-completed {
            background-color: #28a745;
            color: #fff;
        }

        .badge-cancelled {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-view {
            background-color: #17a2b8;
            color: #fff;
        }

        .btn-view:hover {
            background-color: #138496;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <!-- Nút về trang chủ -->
        <div class="mb-3">
            <a href="index.php" class="btn btn-primary">&larr; Trang chủ</a>
        </div>

        <h3 class="mb-4 text-center text-primary">Đơn hàng của tôi</h3>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle text-center bg-white">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['id'] ?></td>
                                <td class="fw-bold text-success"><?= number_format($row['tong_tien']) ?> đ</td>
                                <td>
                                    <?php
                                    $status = $row['trang_thai'];
                                    if ($status == 'Chờ xử lý') {
                                        echo "<span class='badge badge-pending'>Chờ xử lý</span>";
                                    } elseif ($status == 'Hoàn thành') {
                                        echo "<span class='badge badge-completed'>Hoàn thành</span>";
                                    } elseif ($status == 'Đã thanh toán') {
                                        echo "<span class='badge badge-completed'>Đã thanh toán</span>";
                                    } else {
                                        echo "<span class='badge badge-cancelled'>Từ chối</span>";
                                    }
                                    ?>
                                </td>
                                <td><?= date("d/m/Y H:i", strtotime($row['ngay_dat'])) ?></td>
                                <td>
                                    <a href="chi_tiet_don_hang.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-view">
                                        Xem
                                    </a>
                                    <?php
                                    $status = $row['trang_thai'];
                                    $payment = $row['payment_type'] ?? ''; // 'now', 'later', 'cod' hoặc null

                                    // Hiển thị badge trạng thái
                                    if ($status == 'Chờ xử lý') {
                                        if ($payment == 'now') {
                                            echo "<span class='badge bg-success'>✅ Pay Online</span>";
                                        } elseif ($payment == 'cod') {
                                            echo "<span class='badge bg-info'>COD - Chờ admin duyệt</span>";
                                        } else { // chưa thanh toán
                                            echo "<span class='badge bg-warning text-dark'>Chưa thanh toán</span>";
                                        }
                                    } elseif ($status == 'Hoàn thành') {
                                        echo "<span class='badge bg-primary'>Hoàn thành</span>";
                                    } elseif ($status == 'Đã thanh toán') {
                                        echo "<span class='badge bg-success'>✅ Pay Online</span>";
                                    } else {
                                        echo "<span class='badge bg-danger'>Từ chối</span>";
                                    }

                                    // Nút thanh toán nếu đơn chưa thanh toán
                                    if ($status == 'Chờ xử lý' && ($payment == 'later' || $payment == '')): ?>
                                        <form action="checkout_info.php" method="POST" style="display:inline-block; margin-left:5px;">
                                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-success">Thanh Toán</button>
                                        </form>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Bạn chưa có đơn hàng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>