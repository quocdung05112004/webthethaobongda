<?php
// Form đăng ký người dùng (public)
if (session_status() == PHP_SESSION_NONE) session_start();
include __DIR__ . '/../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = isset($_POST['ho_ten']) ? trim($_POST['ho_ten']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
    $so_dien_thoai = isset($_POST['so_dien_thoai']) ? trim($_POST['so_dien_thoai']) : '';
    $dia_chi = isset($_POST['dia_chi']) ? trim($_POST['dia_chi']) : '';

    if ($ho_ten === '' || $email === '' || $password === '' || $password2 === '') {
        $error = 'Vui lòng nhập đầy đủ các trường bắt buộc.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } elseif ($password !== $password2) {
        $error = 'Mật khẩu và xác nhận mật khẩu không khớp.';
    } else {
        // Kiểm tra trùng email
        $stmt = $conn->prepare('SELECT id FROM nguoi_dung WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $exists = $res->fetch_assoc();

        if ($exists) {
            $error = 'Email đã được sử dụng. Vui lòng đăng nhập hoặc dùng email khác.';
        } else {
            // Hash mật khẩu
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare('INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, dia_chi, vai_tro) VALUES (?, ?, ?, ?, ?, 0)');
            $stmt->bind_param('sssss', $ho_ten, $email, $hash, $so_dien_thoai, $dia_chi);
            $ok = $stmt->execute();
            if ($ok) {
                // Lấy id mới và đăng nhập tự động
                $user_id = $conn->insert_id;
                $_SESSION['user'] = [
                    'id' => $user_id,
                    'ho_ten' => $ho_ten,
                    'email' => $email,
                    'vai_tro' => 0
                ];

                // Tái tạo session id để an toàn
                if (function_exists('session_regenerate_id')) session_regenerate_id(true);

                header('Location: index.php'); // sau khi đăng ký quay về trang chủ
                exit;
            } else {
                $error = 'Đăng ký thất bại: ' . $conn->error;
            }
        }
    }
}

ob_start();
?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../css/dangky.css">

<div class="register-wrapper">
    <div class="register-card">

        <h3 class="register-title">Đăng ký tài khoản</h3>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Họ và tên *</label>
                <input type="text" name="ho_ten" class="form-control" required
                       value="<?php echo isset($ho_ten) ? htmlspecialchars($ho_ten) : ''; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu *</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Xác nhận mật khẩu *</label>
                <input type="password" name="password2" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" class="form-control"
                       value="<?php echo isset($so_dien_thoai) ? htmlspecialchars($so_dien_thoai) : ''; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="dia_chi" class="form-control"
                       value="<?php echo isset($dia_chi) ? htmlspecialchars($dia_chi) : ''; ?>">
            </div>

            <button class="btn btn-success w-100 py-2 fs-5">Đăng ký</button>

        </form>
    </div>
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/../view/layout.php';
?>
