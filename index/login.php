<?php
session_start();
include __DIR__ . '/../config/db.php';

// Nếu đã đăng nhập thì chuyển hướng theo vai trò
if (isset($_SESSION['user']) && isset($_SESSION['user']['vai_tro'])) {
    if ($_SESSION['user']['vai_tro'] == 1) {
        header('Location: ../view/admin/home.php');
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    // Loại bỏ khoảng trắng đầu/cuối trong mật khẩu để tránh sai do gõ thừa
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ email và mật khẩu.';
    } else {
        $stmt = $conn->prepare('SELECT id, ho_ten, email, mat_khau, vai_tro FROM nguoi_dung WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if ($user) {
            $stored = $user['mat_khau'];
            // Hỗ trợ mật khẩu mã hóa hoặc plaintext (cơ bản)
            $password_ok = false;
            if (password_verify($password, $stored)) {
                $password_ok = true;
            } elseif ($password === $stored) {
                    $password_ok = true; // dự phòng cho mật khẩu lưu plaintext trong DB mẫu
            }

            if ($password_ok) {
                // lưu thông tin vào session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'ho_ten' => $user['ho_ten'],
                    'email' => $user['email'],
                    'vai_tro' => intval($user['vai_tro'])
                ];
                // Tạo session id mới sau khi đăng nhập để an toàn và tránh mất session
                if (function_exists('session_regenerate_id')) {
                    session_regenerate_id(true);
                }

                if ($user['vai_tro'] == 1) {
                    header('Location: ../view/admin/home.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng.';
            }
        } else {
            $error = 'Tài khoản không tồn tại.';
        }
    }
}

ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3 class="mb-3">Đăng nhập</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary">Đăng nhập</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../view/layout.php';
?>
