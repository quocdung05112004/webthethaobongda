<?php
// sync update for github
session_start();
include '../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$nguoi_dung_id = $_SESSION['user']['id'] ?? 0;
$don_hang_id = $_GET['id'] ?? 0;

// Lấy thông tin đơn hàng
$stmt = $conn->prepare("
    SELECT dh.id, dh.trang_thai, dh.tong_tien, dh.ngay_dat
    FROM don_hang dh
    WHERE dh.id = ? AND dh.nguoi_dung_id = ?
");
$stmt->bind_param("ii", $don_hang_id, $nguoi_dung_id);
$stmt->execute();
$don_hang = $stmt->get_result()->fetch_assoc();

if (!$don_hang) {
    echo "Đơn hàng không tồn tại hoặc bạn không có quyền xem.";
    exit;
}

// Lấy chi tiết sản phẩm trong đơn hàng
$stmt2 = $conn->prepare("
    SELECT sp.ten, sp.hinh_anh, dhct.so_luong, dhct.gia, dhct.thanh_tien
    FROM don_hang_chi_tiet dhct
    JOIN san_pham sp ON dhct.san_pham_id = sp.id
    WHERE dhct.don_hang_id = ?
");
$stmt2->bind_param("i", $don_hang_id);
$stmt2->execute();
$chi_tiet = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?php echo $don_hang['id']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .order-header {
            background: #0d6efd;
            color: #fff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }

        .product-card {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }

        .product-card img {
            max-width: 100px;
            object-fit: cover;
            border-radius: 6px;
        }

        .total {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .status-0 {
            background: #ffc107;
            color: #000;
        }

        /* Chờ xử lý */
        .status-1 {
            background: #0d6efd;
            color: #fff;
        }

        /* Đang giao */
        .status-2 {
            background: #198754;
            color: #fff;
        }

        /* Hoàn thành */
        .status-3 {
            background: #dc3545;
            color: #fff;
        }

        /* Hủy */
    </style>
</head>

<body>
    <div class="container mt-4 mb-5">
        <div class="order-header">
            <h4>Chi tiết đơn hàng #<?php echo $don_hang['id']; ?></h4>
            <div>Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($don_hang['ngay_dat'])); ?></div>
            <div>Trạng thái:
                <span class="status-badge status-<?php
                                                    echo $don_hang['trang_thai'] == 'Chờ xử lý' ? 0 : ($don_hang['trang_thai'] == 'Đang giao' ? 1 : ($don_hang['trang_thai'] == 'Hoàn thành' ? 2 : 3));
                                                    ?>">
                    <?php echo $don_hang['trang_thai']; ?>
                </span>
            </div>
        </div>

        <div class="mt-3">
            <?php foreach ($chi_tiet as $p): ?>
                <div class="product-card d-flex align-items-center gap-3">
                    <img src="../asset/upload/<?php echo $p['hinh_anh']; ?>" alt="<?php echo $p['ten']; ?>">
                    <div class="flex-grow-1">
                        <h6 class="mb-1"><?php echo $p['ten']; ?></h6>
                        <div>Số lượng: <?php echo $p['so_luong']; ?></div>
                        <div>Đơn giá: <?php echo number_format($p['gia']); ?> đ</div>
                    </div>
                    <div class="text-end total">
                        <?php echo number_format($p['thanh_tien']); ?> đ
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="product-card d-flex justify-content-end total">
                Tổng tiền: <?php echo number_format($don_hang['tong_tien']); ?> đ
            </div>
        </div>

        <div class="mt-3">
            <a href="don_hang.php" class="btn btn-secondary">← Quay lại danh sách đơn hàng</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>