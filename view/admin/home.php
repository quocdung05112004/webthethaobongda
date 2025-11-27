<?php
include '../../config/db.php';

// Thống kê demo
$total_users = $conn->query("SELECT COUNT(*) as total FROM nguoi_dung")->fetch_assoc()['total'];
$total_products = $conn->query("SELECT COUNT(*) as total FROM san_pham")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) as total FROM don_hang")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(tong_tien) as total FROM don_hang")->fetch_assoc()['total'];

ob_start();
?>

<h2>Dashboard Admin</h2>
<div class="row mt-4">

    <div class="col-md-3 mb-3">
        <div class="card card-stats card-users p-3">
            <h5>Người dùng</h5>
            <h3><?php echo $total_users; ?></h3>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card card-stats card-products p-3">
            <h5>Sản phẩm</h5>
            <h3><?php echo $total_products; ?></h3>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card card-stats card-orders p-3">
            <h5>Đơn hàng</h5>
            <h3><?php echo $total_orders; ?></h3>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card card-stats card-revenue p-3">
            <h5>Doanh thu</h5>
            <h3><?php echo number_format($total_revenue); ?> đ</h3>
        </div>
    </div>

</div>


<!-- ======================= BIỂU ĐỒ ======================= -->
<div class="card mt-5 p-4">
    <h4 class="mb-4">Biểu đồ tổng quan</h4>

    <canvas id="myChart" style="width:100%; max-height:400px;"></canvas>
</div>

<!-- ChartJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'bar', // bar, line, pie…
        data: {
            labels: ['Người dùng', 'Sản phẩm', 'Đơn hàng', 'Doanh thu'],
            datasets: [{
                label: 'Thống kê hệ thống',
                data: [
                    <?php echo $total_users; ?>,
                    <?php echo $total_products; ?>,
                    <?php echo $total_orders; ?>,
                    <?php echo $total_revenue ?: 0; ?>
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>