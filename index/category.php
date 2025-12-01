<?php
include '../config/db.php';

$dm_id = $_GET['id'] ?? 0;

// Lấy tên danh mục
$sql_dm = "SELECT * FROM danh_muc WHERE id = $dm_id";
$dm = $conn->query($sql_dm)->fetch_assoc();
if(!$dm){
    die("Danh mục không tồn tại!");
}

ob_start();
?>

<h2 class="mb-4">Danh mục: <?php echo $dm['ten']; ?></h2>

<div class="row g-4"> <!-- g-4 để khoảng cách đồng đều -->

<?php
$sql_sp = "SELECT * FROM san_pham WHERE danh_muc_id = $dm_id ORDER BY id DESC";
$result_sp = $conn->query($sql_sp);

if($result_sp->num_rows > 0):
    while($sp = $result_sp->fetch_assoc()):

        // Kiểm tra ảnh, nếu trống dùng ảnh mặc định
        $img_path = !empty($sp['hinh_anh']) && file_exists("../asset/upload/".$sp['hinh_anh'])
                    ? "../asset/upload/".$sp['hinh_anh']
                    : "../asset/upload/default.png"; // bạn tạo file default.png
?>
    <div class="col-6 col-md-3"> <!-- 4 sản phẩm 1 hàng desktop, 2 mobile -->
        <div class="card h-100 d-flex flex-column">
            <div class="card-img-wrapper">
                <img src="<?php echo $img_path; ?>" class="card-img-top product-img" alt="<?php echo $sp['ten']; ?>">
            </div>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title mb-2"><?php echo $sp['ten']; ?></h6>
                <p class="text-danger fw-bold mb-3"><?php echo number_format($sp['gia']); ?> đ</p>
                <a href="product.php?id=<?php echo $sp['id']; ?>" class="btn btn-primary btn-sm mt-auto mb-1">Xem chi tiết</a>
                <button class="btn btn-success btn-sm mt-1 add-to-cart" data-id="<?php echo $sp['id']; ?>">🛒 Thêm vào giỏ</button>
            </div>
        </div>
    </div>
<?php
    endwhile;
else:
    echo "<p>Không có sản phẩm nào trong danh mục này.</p>";
endif;
?>

</div>
<link rel="stylesheet" href="../css/danhmuc.css">

<?php
$content = ob_get_clean();
include '../view/layout.php';
