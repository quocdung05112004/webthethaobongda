<?php
session_start();
include '../config/db.php';

// Lấy giỏ hàng
if(isset($_SESSION['user'])){
    $user_id = $_SESSION['user']['id'];
    $sql = "SELECT gh.id AS cart_id, sp.id AS sp_id, sp.ten, sp.hinh_anh, sp.gia, gh.so_luong
            FROM gio_hang gh
            JOIN san_pham sp ON gh.san_pham_id = sp.id
            WHERE gh.nguoi_dung_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $cart_items = [];
    while($row = mysqli_fetch_assoc($result)){
        $cart_items[] = $row;
    }
} else {
    if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $cart_items = $_SESSION['cart'];
}

ob_start();
?>

<h2>Giỏ hàng</h2>

<?php if(count($cart_items)>0): ?>
<table class="table table-bordered align-middle">
    <tr>
        <th>Hình</th>
        <th>Sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
        <th>Thao tác</th>
    </tr>
    <?php
    $tong = 0;
    foreach($cart_items as $key=>$item):
        $gia = $item['gia'];
        $so_luong = $item['so_luong'];
        $thanh_tien = $gia*$so_luong;
        $tong += $thanh_tien;
    ?>
    <tr>
        <td><img src="../asset/upload/<?php echo $item['hinh_anh'];?>" width="80"></td>
        <td>
            <a href="product.php?id=<?php echo isset($item['sp_id'])?$item['sp_id']:$item['id']; ?>">
                <?php echo $item['ten'];?>
            </a>
        </td>
        <td><?php echo number_format($gia); ?> đ</td>
        <td>
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-secondary me-1 update-qty" data-key="<?php echo $key;?>" data-action="minus">-</button>
                <span class="mx-1 qty-text"><?php echo $so_luong;?></span>
                <button class="btn btn-sm btn-secondary ms-1 update-qty" data-key="<?php echo $key;?>" data-action="plus">+</button>
            </div>
        </td>
        <td><?php echo number_format($thanh_tien); ?> đ</td>
        <td>
            <button class="btn btn-danger btn-sm delete-item" data-key="<?php echo $key;?>">Xóa</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h4>Tổng tiền: <?php echo number_format($tong); ?> đ</h4>

<?php if(isset($_SESSION['user'])): ?>
    <a href="checkout.php" class="btn btn-success btn-lg mt-2">Thanh toán</a>
<?php else: ?>
    <a href="login.php?redirect=checkout.php" class="btn btn-warning btn-lg mt-2">Đăng nhập để thanh toán</a>
<?php endif; ?>

<?php else: ?>
<div class="alert alert-info">Giỏ hàng trống. <a href="index.php">Mua sắm ngay</a></div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Cập nhật số lượng
    $('.update-qty').click(function(){
        var key = $(this).data('key');
        var action = $(this).data('action');
        $.post('update_cart.php', {key:key, action:action}, function(res){
            location.reload(); // hoặc update DOM trực tiếp
        });
    });

    // Xóa sản phẩm
    $('.delete-item').click(function(){
        if(!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
        var key = $(this).data('key');
        $.post('delete_cart.php', {key:key}, function(res){
            location.reload();
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include '../view/layout.php';
