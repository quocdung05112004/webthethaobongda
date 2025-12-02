<?php
session_start();
include __DIR__ . '/../config/db.php';

// Nếu đã đăng nhập thì chuyển hướng
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
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ email và mật khẩu.';
    } else {
        $stmt = $conn->prepare('SELECT id, ho_ten, email, mat_khau, vai_tro FROM nguoi_dung WHERE email=? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if ($user) {
            $stored = $user['mat_khau'];
            $password_ok = password_verify($password, $stored) || $password === $stored;

            if ($password_ok) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'ho_ten' => $user['ho_ten'],
                    'email' => $user['email'],
                    'vai_tro' => intval($user['vai_tro'])
                ];

                session_regenerate_id(true);

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
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - SPORTSHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
        }

        .login-box {
            margin-top: 80px;
        }
    </style>
</head>

<body>

    <div class="container login-box">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4 shadow-sm">

                    <h3 class="mb-3 text-center">Đăng nhập</h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">Đăng nhập</button>

                        <div class="text-center mt-3">
                            <a href="register.php">Chưa có tài khoản? Đăng ký</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>

</html>