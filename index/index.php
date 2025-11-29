<?php
include '../config/db.php';

// Lấy tất cả danh mục
$sql_dm = "SELECT * FROM danh_muc ORDER BY id ASC";
$result_dm = $conn->query($sql_dm);

ob_start();
?>

<!-- Banner Slider (Bootstrap) -->
<div id="bannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
        $banners = ['banner1.jpg','banner2.jpg','banner4.jpg','banner5.jpg','banner6.jpg'];
        foreach($banners as $index => $banner): ?>
            <div class="carousel-item <?php echo $index===0?'active':'';?>">
                <img src="../asset/upload/<?php echo $banner;?>" class="d-block mx-auto" alt="Banner <?php echo $index+1;?>">
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<?php while($dm = $result_dm->fetch_assoc()): ?>
    <h3 class="mb-3 mt-4"><?php echo $dm['ten'];?></h3>

    <?php
    $sql_sp = "SELECT * FROM san_pham WHERE danh_muc_id = ".$dm['id']." ORDER BY id DESC";
    $result_sp = $conn->query($sql_sp);
    
    if($result_sp->num_rows>0):
    ?>
    <div class="product-slider-<?php echo $dm['id']; ?> mb-4">
        <?php while($sp=$result_sp->fetch_assoc()): ?>
            <div class="card product-card mx-2 position-relative" style="min-width:200px;">
                <img src="../asset/upload/<?php echo $sp['hinh_anh'];?>" class="card-img-top" alt="<?php echo $sp['ten'];?>">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title"><?php echo $sp['ten'];?></h6>
                    <p class="text-danger fw-bold"><?php echo number_format($sp['gia']);?> đ</p>
                    <a href="product.php?id=<?php echo $sp['id'];?>" class="btn btn-primary mt-auto btn-sm mb-1">Xem chi tiết</a>
                    <!-- Nút thêm vào giỏ hàng -->
                    <button class="btn btn-success btn-sm add-to-cart" 
                            data-id="<?php echo $sp['id'];?>" 
                            style="margin-top:5px;">
                        🛒 Thêm vào giỏ
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
<?php endwhile; ?>

<style>
.product-card img { height:180px; object-fit:contain; width:100%; }
.product-card { border:1px solid #ddd; border-radius:5px; }
</style>

<script>
$(document).ready(function(){
    <?php
    $result_dm->data_seek(0);
    while($dm = $result_dm->fetch_assoc()):
    ?>
    $('.product-slider-<?php echo $dm['id']; ?>').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            { breakpoint: 992, settings: { slidesToShow: 3 } },
            { breakpoint: 768, settings: { slidesToShow: 2 } },
            { breakpoint: 576, settings: { slidesToShow: 1 } }
        ]
    });
    <?php endwhile; ?>

    // ========== AJAX Thêm vào giỏ hàng ==========
    $('.add-to-cart').click(function(){
        var sp_id = $(this).data('id');
        $.ajax({
            url: 'add_cart.php',
            type: 'POST',
            data: {product_id: sp_id},
            success: function(res){
                alert('Đã thêm sản phẩm vào giỏ hàng!');
                // TODO: update số lượng giỏ hàng trên navbar nếu muốn
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include '../view/layout.php';
?>
