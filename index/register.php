<?php
session_start();
include __DIR__ . '/../config/db.php';

// Nếu đã login rồi thì không cần đăng ký tiếp
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = trim($_POST['ho_ten'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    // Kiểm tra rỗng
    if ($ho_ten === '' || $email === '' || $password === '') {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } elseif ($password !== $confirm) {
        $error = "Mật khẩu nhập lại không khớp.";
    } else {

        // Kiểm tra email tồn tại
        $check = $conn->prepare("SELECT id FROM nguoi_dung WHERE email = ? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Email này đã được sử dụng!";
        } else {
            // Hash mật khẩu
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng mới
            $stmt = $conn->prepare("INSERT INTO nguoi_dung (ho_ten, email, mat_khau, vai_tro) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss", $ho_ten, $email, $hash);

            if ($stmt->execute()) {
                $success = "Đăng ký thành công! Bạn có thể đăng nhập.";

                // Nếu muốn TỰ ĐĂNG NHẬP SAU ĐĂNG KÝ → bật đoạn này:
                //                $_SESSION['user'] = [
                //                    'id' => $stmt->insert_id,
                //                    'ho_ten' => $ho_ten,
                //                    'email' => $email,
                //                    'vai_tro' => 0
                //                ];
                //                header("Location: index.php");
                //                exit;

            } else {
                $error = "Đã xảy ra lỗi. Vui lòng thử lại.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký - SPORTSHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
        }

        .register-box {
            margin-top: 70px;
        }
    </style>
</head>

<body>

    <div class="container register-box">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card p-4 shadow-sm">
                    <h3 class="mb-3 text-center">Đăng ký tài khoản</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <div class="text-center mb-3">
                            <a href="login.php" class="btn btn-success w-100">Đến trang đăng nhập</a>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Họ và tên:</label>
                            <input type="text" name="ho_ten" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nhập lại mật khẩu:</label>
                            <input type="password" name="confirm" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">Đăng ký</button>

                        <div class="text-center mt-3">
                            <a href="login.php">Đã có tài khoản? Đăng nhập</a>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

</body>

</html>