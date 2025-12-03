<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-dark text-white text-center rounded-top-4">
                        <h4 class="mb-0">ADMIN LOGIN</h4>
                    </div>

                    <div class="card-body p-4">

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center">
                                <?= $_SESSION['error'];
                                unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="xu_ly_admin_login.php">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Nhập email" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password" name="mat_khau" class="form-control" placeholder="Nhập mật khẩu" required>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-2 rounded-3">
                                Đăng nhập
                            </button>
                        </form>

                    </div>

                    <div class="card-footer text-center small text-muted">
                        © <?= date('Y') ?> Admin Panel
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>