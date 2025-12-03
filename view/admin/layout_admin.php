<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    

    <style>
        body {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background-color: #343a40;
            min-height: 100vh;
            color: #fff;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            color: #fff;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .main {
            flex-grow: 1;
            padding: 20px;
        }

        .card-stats {
            color: #fff;
        }

        .card-users {
            background-color: #0d6efd;
        }

        .card-products {
            background-color: #198754;
        }

        .card-orders {
            background-color: #ffc107;
        }

        .card-revenue {
            background-color: #dc3545;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h3 class="text-center py-3">ADMIN PANEL</h3>
        <a href="home.php">Dashboard</a>
        <a href="quanly_SanPham.php">Quản lý sản phẩm</a>
        <a href="orders.php">Quản lý đơn hàng</a>
        <a href="users.php">Quản lý người dùng</a>
        <a href="logout.php">Đăng xuất</a>
    </div>

    <div class="main">
        <?php echo $content; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>