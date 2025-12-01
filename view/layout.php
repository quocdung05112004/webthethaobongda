<?php
// layout.php
session_start(); // Bắt buộc phải ở đầu file trước bất kỳ output nào
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>SPORTSHOP - Web bán đồ thể thao</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
            color: #0d6efd !important;
            font-size: 22px;
        }

        .product-card img {
            height: 220px;
            object-fit: cover;
            width: 100%;
        }

        .footer {
            background: #222;
            padding: 20px 0;
            color: #ccc;
            margin-top: 30px;
        }

        #chatbot::-webkit-scrollbar {
            width: 6px;
        }

        #chatbot::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">SPORTSHOP</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Giỏ hàng</a></li>

                    <?php if (isset($_SESSION['user'])): ?>
                        <!-- Người dùng đã đăng nhập -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <?php echo htmlspecialchars($_SESSION['user']['email']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Chưa đăng nhập -->
                        <li class="nav-item"><a class="nav-link" href="login.php">Đăng nhập</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container mt-4">
        <?php
        // Biến $content phải được khai báo trước khi include layout.php
        if (isset($content)) {
            echo $content;
        }
        ?>
    </div>

    <!-- FOOTER -->
    <div class="footer text-center">
        <p>© 2025 SPORTSHOP - Web bán đồ thể thao</p>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mở/đóng chatbot
            $('#chatbot-header').click(function() {
                $('#chatbot-body').slideToggle();
            });

            function appendMessage(sender, message) {
                let html = '<div style="margin-bottom:5px;"><b>' + sender + ':</b> ' + message + '</div>';
                $('#chat-box').append(html);
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            }

            function appendProduct(p) {
                let html = `<div style="margin:5px 0; border:1px solid #ddd; padding:5px; border-radius:5px;">
                        <a href="${p.link}" target="_blank">
                            <img src="${p.hinh_anh}" style="width:100%; height:100px; object-fit:cover; border-radius:5px;">
                            <div>${p.ten}</div>
                            <div style="color:red">${p.gia}</div>
                        </a>
                    </div>`;
                $('#chat-box').append(html);
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            }

            $('#chat-send').click(function() {
                let msg = $('#chat-input').val().trim();
                if (!msg) return;
                appendMessage('Bạn', msg);
                $('#chat-input').val('');

                $.post('chatbot_ai_simple.php', {
                    message: msg
                }, function(data) {
                    appendMessage('Bot', data.reply);
                    if (data.products) {
                        data.products.forEach(p => appendProduct(p));
                    }
                }, 'json');
            });

            $('#chat-input').keypress(function(e) {
                if (e.which == 13) {
                    $('#chat-send').click();
                }
            });
        });
    </script>

    <!-- Chatbot popup góc phải -->
    <div id="chatbot" style="position:fixed; bottom:20px; right:20px; width:300px; max-height:400px; box-shadow:0 0 10px rgba(0,0,0,0.3); border-radius:10px; overflow:hidden; z-index:9999;">
        <div id="chatbot-header" style="background:#0d6efd; color:#fff; padding:10px; cursor:pointer;">
            Chat tư vấn
        </div>
        <div id="chatbot-body" style="display:none; background:#fff; height:300px; display:flex; flex-direction:column;">
            <div id="chat-box" style="flex:1; overflow-y:auto; padding:10px; border-bottom:1px solid #ddd;"></div>
            <div style="display:flex; border-top:1px solid #ddd;">
                <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." style="flex:1; padding:8px; border:none;">
                <button id="chat-send" style="background:#0d6efd; color:#fff; border:none; padding:0 15px;">Gửi</button>
            </div>
        </div>
    </div>

</body>

</html>