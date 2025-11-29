<?php
session_start();
include '../config/db.php';

// Kiểm tra đăng nhập
if(!isset($_SESSION['user'])){
    header('Location: login.php?redirect=checkout.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

// Lấy giỏ hàng
$cart_items = [];
if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
    foreach($_SESSION['cart'] as $id => $item){
        $cart_items[] = $item;
    }
} else {
    // Lấy từ DB
    $sql = "SELECT gh.san_pham_id AS id, sp.ten, sp.hinh_anh, sp.gia, gh.so_luong
            FROM gio_hang gh
            JOIN san_pham sp ON gh.san_pham_id = sp.id
            WHERE gh.nguoi_dung_id=$user_id";
    $res = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($res)){
        $cart_items[] = $row;
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(count($cart_items) === 0){
        $error = "Giỏ hàng trống!";
    } else {
        // Tính tổng tiền
        $tong = 0;
        foreach($cart_items as $item){
            $tong += $item['gia'] * $item['so_luong'];
        }

        // Tạo đơn hàng
        $stmt = $conn->prepare("INSERT INTO don_hang (nguoi_dung_id, tong_tien, trang_thai) VALUES (?, ?, ?)");
        $trang_thai = "Chờ xử lý";
        $stmt->bind_param("ids", $user_id, $tong, $trang_thai);
        $stmt->execute();
        $don_hang_id = $stmt->insert_id;

        // Thêm chi tiết đơn hàng
        $stmt_detail = $conn->prepare("INSERT INTO don_hang_chi_tiet (don_hang_id, san_pham_id, so_luong, gia, thanh_tien) VALUES (?, ?, ?, ?, ?)");
        foreach($cart_items as $item){
            $thanh_tien = $item['gia'] * $item['so_luong'];
            $stmt_detail->bind_param("iiidd", $don_hang_id, $item['id'], $item['so_luong'], $item['gia'], $thanh_tien);
            $stmt_detail->execute();
        }

        // Xóa giỏ hàng
        mysqli_query($conn, "DELETE FROM gio_hang WHERE nguoi_dung_id=$user_id");
        unset($_SESSION['cart']);

        $success = "Đơn hàng đã được đặt thành công!";
    }
}

ob_start();
?>

<h2>Thanh toán</h2>

<?php if(isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <a href="index.php" class="btn btn-primary">Quay lại trang chủ</a>
<?php else: ?>

<?php if(count($cart_items) === 0): ?>
    <div class="alert alert-info">Giỏ hàng trống. <a href="index.php">Mua sắm ngay</a></div>
<?php else: ?>
<table class="table table-bordered align-middle">
    <tr>
        <th>Hình</th>
        <th>Sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
    </tr>
    <?php $tong = 0; foreach($cart_items as $item): 
        $thanh_tien = $item['gia'] * $item['so_luong'];
        $tong += $thanh_tien;
    ?>
    <tr>
        <td><img src="../asset/upload/<?php echo $item['hinh_anh']; ?>" width="80"></td>
        <td><?php echo $item['ten']; ?></td>
        <td><?php echo number_format($item['gia']); ?> đ</td>
        <td><?php echo $item['so_luong']; ?></td>
        <td><?php echo number_format($thanh_tien); ?> đ</td>
    </tr>
    <?php endforeach; ?>
</table>

<h4>Tổng tiền: <?php echo number_format($tong); ?> đ</h4>

<form method="POST">
    <button type="submit" class="btn btn-success btn-lg">Đặt hàng</button>
</form>

<?php endif; ?>
<?php endif; ?>

<?php
$content = ob_get_clean();
include '../view/layout.php';
?>
