<?php
session_start();
include __DIR__ . '/../config/db.php'; // sửa đường dẫn đúng

// Nếu đã đăng nhập thì chuyển hướng theo vai trò
if (isset($_SESSION['user']) && isset($_SESSION['user']['vai_tro'])) {
    if ($_SESSION['user']['vai_tro'] == 1) {
        header('Location: ../view/admin/home.php');
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ email và mật khẩu.';
    } else {
        $stmt = $conn->prepare('SELECT id, ho_ten, email, mat_khau, vai_tro FROM nguoi_dung WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if ($user) {
            $stored = $user['mat_khau'];
            $password_ok = false;

            if (password_verify($password, $stored)) {
                $password_ok = true;
            } elseif ($password === $stored) {
                $password_ok = true; // dự phòng cho mật khẩu plaintext trong DB mẫu
            }

            if ($password_ok) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'ho_ten' => $user['ho_ten'],
                    'email' => $user['email'],
                    'vai_tro' => intval($user['vai_tro'])
                ];

                if (function_exists('session_regenerate_id')) {
                    session_regenerate_id(true);
                }

                // --- Hợp nhất giỏ hàng session vào DB ---
                if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                    $user_id = $user['id'];
                    foreach($_SESSION['cart'] as $product_id => $item){
                        $so_luong = intval($item['so_luong']);
                        $res_cart = mysqli_query($conn,"SELECT * FROM gio_hang WHERE nguoi_dung_id=$user_id AND san_pham_id=$product_id");
                        if(mysqli_num_rows($res_cart) > 0){
                            mysqli_query($conn,"UPDATE gio_hang SET so_luong = so_luong + $so_luong WHERE nguoi_dung_id=$user_id AND san_pham_id=$product_id");
                        } else {
                            mysqli_query($conn,"INSERT INTO gio_hang(nguoi_dung_id, san_pham_id, so_luong) VALUES($user_id, $product_id, $so_luong)");
                        }
                    }
                    unset($_SESSION['cart']); // Xóa giỏ hàng session sau khi hợp nhất
                }
                // --- Kết thúc hợp nhất ---

                if ($user['vai_tro'] == 1) {
                    header('Location: ../view/admin/home.php');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng.';
            }
        } else {
            $error = 'Tài khoản không tồn tại.';
        }
    }
}

ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3 class="mb-3">Đăng nhập</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../view/layout.php';
?>
