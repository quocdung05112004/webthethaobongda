<?php
// Bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên file hiện tại
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Web Thể Thao</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            font-weight: bold;
            color: #0d6efd !important;
            font-size: 22px;
        }

        .product-card img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }

        .container.flex-grow-1 {
            flex: 1 0 auto; /* Nội dung chính chiếm không gian còn lại */
        }

        .footer {
            background: #222;
            padding: 20px 0;
            color: #ccc;
            margin-top: 30px;
            flex-shrink: 0;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">SPORTSHOP</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Giỏ hàng</a></li>

                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Xin chào <?php echo $_SESSION['user']['ho_ten']; ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Đăng xuất</a>
                        </li>
                    <?php else: ?>
                        <?php if ($current_page !== 'login.php'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Đăng nhập</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($current_page !== 'register.php'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Đăng ký</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container mt-4 flex-grow-1">
        <?php echo $content; ?>
    </div>

    <!-- FOOTER -->
    <div class="footer text-center">
        <p>© 2025 SPORTSHOP - Web bán đồ thể thao</p>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
