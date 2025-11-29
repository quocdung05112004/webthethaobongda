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
    <link rel="stylesheet" href="../css/style.css">
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
                    <!-- Giỏ hàng -->
                    <!-- Giỏ hàng -->
<li class="nav-item me-3">
    <a class="nav-link position-relative" href="cart.php">
        <i class="bi bi-cart-fill cart-icon"></i>
        <span class="cart-count" id="cart-count">
            <?php
            // Lấy số lượng ban đầu
            if(isset($_SESSION['cart'])){
                echo count($_SESSION['cart']);
            } elseif(isset($_SESSION['user'])){
                include '../config/db.php';
                $user_id = $_SESSION['user']['id'];
                $res = mysqli_query($conn,"SELECT COUNT(*) AS total FROM gio_hang WHERE nguoi_dung_id=$user_id");
                $row = mysqli_fetch_assoc($res);
                echo $row['total'] ?? 0;
            } else {
                echo 0;
            }
            ?>
        </span>
    </a>
</li>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateCartCount(newCount){
    $('#cart-count').text(newCount);
}

$(document).ready(function(){
    // Khi nhấn thêm sản phẩm
    $('.btn-add-cart').click(function(e){
        e.preventDefault();
        var sp_id = $(this).data('id');

        $.post('add_cart.php', {product_id: sp_id, so_luong:1}, function(res){
            // Lấy số lượng mới từ server
            $.get('cart_count.php', function(count){
                updateCartCount(count);
            });
        });
    });

    // Định kỳ đồng bộ giỏ hàng (tùy chọn, ví dụ 5s/lần)
    setInterval(function(){
        $.get('cart_count.php', function(count){
            updateCartCount(count);
        });
    }, 1000);
});
</script>


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
        <p>SPORTSHOP - Web bán đồ thể thao</p>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>