<?php
include __DIR__ . '/../../config/db.php';

$id = $_GET['id'];
$message = "";

// Lấy thông tin sản phẩm
$data = $conn->query("SELECT * FROM san_pham WHERE id = $id")->fetch_assoc();

if (isset($_POST['update'])) {
    $ten = $_POST['ten'];
    $gia = $_POST['gia'];
    $mo_ta = $_POST['mo_ta'];

    $hinh = $data["hinh_anh"]; // giữ ảnh cũ

    // Nếu có upload ảnh mới
    if (!empty($_FILES['hinh']['name'])) {
        $hinh = "uploads/" . basename($_FILES["hinh"]["name"]);
        move_uploaded_file($_FILES["hinh"]["tmp_name"], __DIR__ . "/uploads/" . basename($_FILES["hinh"]["name"]));
    }

    $sql = "UPDATE san_pham SET 
            ten='$ten', gia='$gia', mo_ta='$mo_ta', hinh_anh='$hinh'
            WHERE id=$id";

    if ($conn->query($sql)) {
        $message = "Cập nhật thành công!";
        $data = $conn->query("SELECT * FROM san_pham WHERE id = $id")->fetch_assoc();
    } else {
        $message = "Lỗi: " . $conn->error;
    }
}

ob_start();
?>

<h2>Sửa sản phẩm</h2>
<p style="color: green;"><?= $message ?></p>

<form method="POST" enctype="multipart/form-data">
    <label>Tên sản phẩm</label>
    <input class="form-control mb-2" type="text" name="ten" value="<?= $data['ten'] ?>" required>

    <label>Giá</label>
    <input class="form-control mb-2" type="number" name="gia" value="<?= $data['gia'] ?>" required>

    <label>Mô tả</label>
    <textarea class="form-control mb-2" name="mo_ta"><?= $data['mo_ta'] ?></textarea>

    <label>Hình ảnh</label>
    <input class="form-control mb-2" type="file" name="hinh">
    <img src="<?= $data['hinh_anh'] ?>" width="120">

    <button type="submit" class="btn btn-success" name="update">Cập nhật</button>
</form>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
