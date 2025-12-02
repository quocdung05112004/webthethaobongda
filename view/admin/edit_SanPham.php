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

    $hinh = $data["hinh_anh"]; // giữ ảnh cũ nếu không upload mới

    // Nếu có upload ảnh mới
    if (!empty($_FILES['hinh']['name'])) {
        $ten_file = basename($_FILES["hinh"]["name"]);
        $upload_dir = __DIR__ . "/../../asset/upload/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // tạo thư mục nếu chưa có
        }
        move_uploaded_file($_FILES["hinh"]["tmp_name"], $upload_dir . $ten_file);
        $hinh = "../../asset/upload/" . $ten_file; // đường dẫn lưu DB
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
    <?php if ($data['hinh_anh']): ?>
        <img src="<?= $data['hinh_anh'] ?>" width="120">
    <?php endif; ?>

    <button type="submit" class="btn btn-success" name="update">Cập nhật</button>
</form>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>