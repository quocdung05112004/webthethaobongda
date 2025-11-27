<?php
include '../config/db.php';

// Lấy sản phẩm
$sql = "SELECT * FROM san_pham";
$result = $conn->query($sql);

ob_start(); // bắt đầu lưu giao diện con vào $content
?>

<h2 class="mb-4">Sản phẩm mới nhất</h2>

<div class="row">

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="col-md-3 mb-4">
            <div class="card product-card">
                <img src="uploads/<?php echo $row['hinh_anh']; ?>" class="card-img-top">

                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['ten']; ?></h5>
                    <p class="text-danger fw-bold"><?php echo number_format($row['gia']); ?> đ</p>
                    <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary w-100">Xem chi tiết</a>
                </div>
            </div>
        </div>
    <?php } ?>

</div>

<?php
$content = ob_get_clean(); // lấy nội dung
include '../view/layout.php';
