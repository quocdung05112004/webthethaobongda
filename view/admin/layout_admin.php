<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">



    <style>
        body {
            min-height: 100vh;
            display: flex;
            background: #f4f6f9;
        }

        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, #212529, #343a40);
            min-height: 100vh;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.15);
        }

        .sidebar .brand {
            text-align: center;
            padding: 20px 10px;
            font-size: 20px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-size: 15px;
        }

        .sidebar a i {
            width: 24px;
            font-size: 18px;
            margin-right: 10px;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left: 4px solid #0d6efd;
        }

        .sidebar a.active {
            background: rgba(13, 110, 253, 0.2);
            color: #fff;
            border-left: 4px solid #0d6efd;
        }

        .main {
            margin-left: 240px;
            padding: 25px;
            width: 100%;
        }
    </style>

</head>

<body>

    <div class="sidebar">
        <div class="brand">ADMIN PANEL</div>

        <a href="home.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="quanly_SanPham.php"><i class="bi bi-box-seam"></i> Quản lý sản phẩm</a>
        <a href="orders.php"><i class="bi bi-receipt"></i> Duyệt đơn hàng</a>
        <a href="orders_done.php"><i class="bi bi-check-circle"></i> Lịch sử đã duyệt</a>
        <a href="users.php"><i class="bi bi-people"></i> Quản lý người dùng</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
    </div>



    <div class="main">
        <?php echo $content; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>