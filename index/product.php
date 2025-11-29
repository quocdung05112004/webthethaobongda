<?php
session_start();
include '../config/db.php';

// Lấy id sản phẩm từ URL
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: index.php");
    exit();
}

$product_id = $_GET['id'];

// Lấy thông tin sản phẩm
$sql = "SELECT * FROM san_pham WHERE id=$product_id";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) == 0){
    echo "<h3>Sản phẩm không tồn tại</h3>";
    exit();
}

$product = mysqli_fetch_assoc($res);

ob_start();
?>

<div class="row">
    <div class="col-md-6">
        <img src="../asset/upload/<?php echo $product['hinh_anh'];?>" class="img-fluid" alt="<?php echo $product['ten'];?>">
    </div>
    <div class="col-md-6">
        <h2><?php echo $product['ten']; ?></h2>
        <p class="text-danger fw-bold" style="font-size:1.5rem;"><?php echo number_format($product['gia']); ?> đ</p>
        <p><?php echo nl2br($product['mo_ta']); ?></p>

        <div class="d-flex align-items-center mb-3">
            <button class="btn btn-secondary me-2" id="qty-minus">-</button>
            <input type="text" id="qty" value="1" style="width:50px; text-align:center;" readonly>
            <button class="btn btn-secondary ms-2" id="qty-plus">+</button>
        </div>

        <button class="btn btn-success" id="add-cart" data-id="<?php echo $product['id']; ?>">🛒 Thêm vào giỏ hàng</button>
        <a href="index.php" class="btn btn-outline-secondary ms-2">Quay lại</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Tăng giảm số lượng
    $('#qty-plus').click(function(){
        var val = parseInt($('#qty').val());
        $('#qty').val(val+1);
    });
    $('#qty-minus').click(function(){
        var val = parseInt($('#qty').val());
        if(val>1) $('#qty').val(val-1);
    });

    // Thêm vào giỏ hàng
    $('#add-cart').click(function(){
        var product_id = $(this).data('id');
        var so_luong = parseInt($('#qty').val());
        $.post('add_cart.php', {product_id: product_id, so_luong: so_luong}, function(res){
            alert('Đã thêm sản phẩm vào giỏ hàng!');
            // TODO: update số lượng giỏ hàng trên navbar nếu muốn
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include '../view/layout.php';
