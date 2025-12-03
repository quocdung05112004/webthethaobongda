<?php
session_start();
include __DIR__ . '/../config/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];
$thong_bao = "";

// Lấy thông tin tài khoản
$stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE id=? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("Không tìm thấy người dùng!");
}

// Cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = $_POST['ho_ten'] ?? '';
    $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';
    $dia_chi = $_POST['dia_chi'] ?? '';

    $update = $conn->prepare(
        "UPDATE nguoi_dung SET ho_ten=?, so_dien_thoai=?, dia_chi=? WHERE id=?"
    );
    $update->bind_param("sssi", $ho_ten, $so_dien_thoai, $dia_chi, $user_id);

    if ($update->execute()) {
        $thong_bao = "<div class='alert alert-success'>Cập nhật thành công!</div>";

        // Cập nhật session
        $_SESSION['user']['ho_ten'] = $ho_ten;

        // Load lại dữ liệu mới
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    } else {
        $thong_bao = "<div class='alert alert-danger'>Lỗi cập nhật!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hồ sơ cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .profile-box {
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <div class="container profile-box">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card p-4 shadow-sm">

                    <h3 class="mb-3 text-center">Hồ sơ người dùng</h3>

                    <?= $thong_bao ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Họ tên</label>
                            <input type="text" name="ho_ten" class="form-control" value="<?= $user['ho_ten'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email (không thể thay đổi)</label>
                            <input type="email" class="form-control" value="<?= $user['email'] ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="so_dien_thoai" class="form-control" value="<?= $user['so_dien_thoai'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="dia_chi" class="form-control" value="<?= $user['dia_chi'] ?>">
                        </div>

                        <button class="btn btn-primary w-100">Lưu thay đổi</button>

                        <div class="text-center mt-3">
                            <a href="index.php">Trang chủ</a> |
                            <a href="change_password.php">Đổi mật khẩu</a> |
                            <a href="logout.php">Đăng xuất</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>
