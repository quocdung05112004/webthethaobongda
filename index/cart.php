<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - SPORTSHOP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }

        h3 {
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            color: #0d6efd;
        }

        table {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .cart-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-actions button {
            width: 32px;
            height: 32px;
            padding: 0;
            text-align: center;
            font-weight: bold;
            border-radius: 6px;
            transition: 0.2s;
        }

        .cart-actions button:hover {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .cart-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #198754;
        }

        #clear-cart {
            transition: 0.2s;
        }

        #clear-cart:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-checkout {
            background: #198754;
            color: #fff;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-checkout:hover {
            background: #157347;
            color: #fff;
        }

        .btn-continue {
            background: #0d6efd;
            color: #fff;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-continue:hover {
            background: #0b5ed7;
            color: #fff;
        }

        .table thead th {
            background-color: #0d6efd;
            color: #fff;
            text-align: center;
        }

        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }

        .quantity-display {
            display: inline-block;
            width: 32px;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        /* Animation khi số lượng thay đổi */
        .qty-animate {
            transform: scale(1.5);
            color: #0d6efd;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <h3>Giỏ hàng của bạn</h3>

        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tạm tính</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="cart-body"></tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <button id="clear-cart" class="btn btn-outline-danger">Xóa toàn bộ</button>
            <div class="cart-total">Tổng: <span id="cart-total">0 đ</span></div>
        </div>

        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-continue flex-grow-1"><i class="bi bi-arrow-left"></i> Tiếp tục mua sắm</a>
            <button class="btn btn-checkout flex-grow-1"><i class="bi bi-bag-check"></i> Thanh toán</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');

        function animateQty(elem) {
            elem.classList.add('qty-animate');
            setTimeout(() => elem.classList.remove('qty-animate'), 300);
        }

        function renderCart() {
            const tbody = document.getElementById('cart-body');
            tbody.innerHTML = '';
            if (cart.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4">Giỏ hàng trống</td></tr>';
            } else {
                cart.forEach((p, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="d-flex align-items-center gap-2">
                            <img src="${p.img}" class="cart-img">
                            <div>${p.name}</div>
                        </td>
                        <td>${p.price.toLocaleString()} đ</td>
                        <td class="cart-actions d-flex align-items-center justify-content-center gap-1">
                            <button class="btn btn-outline-secondary btn-sm minus" data-index="${index}">-</button>
                            <span class="quantity-display">${p.qty}</span>
                            <button class="btn btn-outline-secondary btn-sm plus" data-index="${index}">+</button>
                        </td>
                        <td>${(p.price*p.qty).toLocaleString()} đ</td>
                        <td>
                            <button class="btn btn-outline-danger btn-sm delete" data-index="${index}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            document.getElementById('cart-total').textContent = cart.reduce((s, p) => s + p.price * p.qty, 0).toLocaleString() + ' đ';
            localStorage.setItem('cart', JSON.stringify(cart));

            document.querySelectorAll('.plus').forEach(btn => {
                btn.onclick = () => {
                    const i = btn.dataset.index;
                    cart[i].qty++;
                    renderCart();
                    const qtyElem = btn.previousElementSibling;
                    animateQty(qtyElem);
                };
            });
            document.querySelectorAll('.minus').forEach(btn => {
                btn.onclick = () => {
                    const i = btn.dataset.index;
                    if (cart[i].qty > 1) {
                        cart[i].qty--;
                        renderCart();
                        const qtyElem = btn.nextElementSibling;
                        animateQty(qtyElem);
                    }
                };
            });
            document.querySelectorAll('.delete').forEach(btn => {
                btn.onclick = () => {
                    const i = btn.dataset.index;
                    cart.splice(i, 1);
                    renderCart();
                };
            });
        }

        document.getElementById('clear-cart').onclick = () => {
            if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
                cart = [];
                renderCart();
            }
        };

        renderCart();
    </script>

</body>

</html>