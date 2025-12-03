<?php
include '../config/db.php';

$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$order_json = $_POST['order_json'];

$order_items = json_decode($order_json, true);

$total = 0;
foreach ($order_items as $item) {
    $total += $item['price'] * $item['qty'];
}

$sql = "INSERT INTO orders(fullname, phone, address, total, created_at)
        VALUES('$fullname', '$phone', '$address', $total, NOW())";

$conn->query($sql);
$order_id = $conn->insert_id;

// Lưu sản phẩm của đơn hàng
foreach ($order_items as $item) {
    $name = $item['name'];
    $price = $item['price'];
    $qty = $item['qty'];
    
    $conn->query("INSERT INTO order_items(order_id, name, price, qty)
                  VALUES($order_id, '$name', $price, $qty)");
}

echo "
<script>
    // Xóa giỏ hàng trong trình duyệt
    localStorage.removeItem('cart');
    localStorage.removeItem('checkout_cart');

    alert('Đặt hàng thành công!');
    window.location.href = 'index.php';
</script>
";
exit;



?>
