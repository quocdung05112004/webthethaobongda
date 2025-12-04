    <?php
    include '../../config/db.php';

    $id = $_GET['id'];

    $conn->query("UPDATE don_hang SET trang_thai = 'Hoàn thành' WHERE id = $id");

    header("Location: orders.php");
    