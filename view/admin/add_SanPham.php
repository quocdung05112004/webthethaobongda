<?php
include __DIR__ . '/../../config/db.php';

$message = "";

// Lấy danh mục ra để hiển thị trong <select>
$ds_danh_muc = $conn->query("SELECT * FROM danh_muc");

if (isset($_POST['add'])) {
    $ten = $_POST['ten'];
    $gia = $_POST['gia'];
    $mo_ta = $_POST['mo_ta'];
    $danh_muc_id = $_POST['danh_muc'];  // Lấy ID danh mục

    // Xử lý ảnh
    $hinh = "";
    if (!empty($_FILES["hinh"]["name"])) {
        $ten_file = basename($_FILES["hinh"]["name"]);
        $hinh = "../../asset/upload/" . $ten_file;
        move_uploaded_file($_FILES["hinh"]["tmp_name"], __DIR__ . "/../../asset/upload/" . $ten_file);
    }

    $sql = "INSERT INTO san_pham (ten, gia, mo_ta, hinh_anh, danh_muc_id)
            VALUES ('$ten', '$gia', '$mo_ta', '$hinh', '$danh_muc_id')";

    if ($conn->query($sql)) {
        $message = "Thêm sản phẩm thành công!";
    } else {
        $message = "Lỗi: " . $conn->error;
    }
}

ob_start();
?>

<h2>Thêm sản phẩm</h2>
<p style="color: green;"><?= $message ?></p>

<form method="POST" enctype="multipart/form-data">

    <label>Tên sản phẩm</label>
    <input class="form-control mb-2" type="text" name="ten" required>

    <label>Giá</label>
    <input class="form-control mb-2" type="number" name="gia" required>

    <label>Mô tả</label>
    <textarea class="form-control mb-2" name="mo_ta"></textarea>

    <label>Danh mục</label>
    <select class="form-control mb-2" name="danh_muc" required>
        <option value="">-- Chọn danh mục --</option>
        <?php while ($row = $ds_danh_muc->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['ten'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Hình ảnh</label>
    <input class="form-control mb-2" type="file" name="hinh">

    <button type="submit" class="btn btn-primary" name="add">Thêm</button>
</form>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
