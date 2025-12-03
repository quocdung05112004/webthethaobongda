<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container py-5">
    <h3 class="mb-4">Thông tin thanh toán</h3>

    <form action="process_checkout.php" method="POST" id="checkout-form">
        <div class="row">
            <div class="col-md-6">
                <h5>Thông tin khách hàng</h5>

                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="fullname" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ giao hàng</label>
                    <input type="text" name="address" required class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <h5>Đơn hàng</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>SL</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody id="order-items"></tbody>
                </table>

                <h4 class="text-end">Tổng thanh toán: <span id="order-total">0 đ</span></h4>

                <input type="hidden" name="order_json" id="order_json">
                
                <button class="btn btn-success w-100 mt-3">Xác nhận thanh toán</button>
            </div>
        </div>
    </form>
</div>

<script>
    let checkoutCart = JSON.parse(localStorage.getItem('checkout_cart') || '[]');
    const tbody = document.getElementById('order-items');
    let total = 0;

    checkoutCart.forEach(item => {
        total += item.price * item.qty;
        tbody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>${item.qty}</td>
                <td>${(item.price * item.qty).toLocaleString()} đ</td>
            </tr>
        `;
    });

    document.getElementById('order-total').textContent = total.toLocaleString() + ' đ';

    // Gửi JSON sang PHP
    document.getElementById('order_json').value = JSON.stringify(checkoutCart);
</script>

</body>
</html>
