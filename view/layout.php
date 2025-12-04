<?php
session_start();
include '../config/db.php';

// Lấy sản phẩm
$result = $conn->query("SELECT * FROM san_pham");
$allProducts = [];
while ($row = $result->fetch_assoc()) {
    $allProducts[] = [
        'id' => $row['id'],
        'ten' => $row['ten'],
        'gia' => (int)$row['gia'],
        'hinh_anh' => "../asset/upload/" . $row['hinh_anh'],
        'mo_ta' => $row['mo_ta'] ?? '',
        'danh_muc_id' => $row['danh_muc_id'] ?? ''
    ];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>SPORTSHOP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/layout.css">
</head>

<body data-theme="<?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark' ? 'dark' : 'light'; ?>">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sticky" style="background: rgba(13,110,253,0.06);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <i class="bi bi-basket3-fill"></i> SPORTSHOP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-1 nav-act" href="index.php" data-act="home">
                            <i class="bi bi-house-door-fill"></i>
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <input id="global-search" class="form-control form-control-sm search-input" type="search" placeholder="Tìm kiếm sản phẩm...">
                    </li>
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link" href="#" id="filtersToggle" data-bs-toggle="dropdown">
                            <i class="bi bi-funnel-fill"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width:280px;">
                            <div class="filters-panel">
                                <h6>Lọc nhanh</h6>
                                <div class="mb-2">
                                    <label class="form-label mb-1">Danh mục</label>
                                    <select id="filter-category" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        <option value="1">Giày thể thao</option>
                                        <option value="2">Quần áo thể thao</option>
                                        <option value="3">Phụ kiện thể thao</option>
                                        <option value="4">Dụng cụ thể thao</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-1">Giá</label>
                                    <div class="d-flex gap-2">
                                        <input id="filter-min" class="form-control form-control-sm" placeholder="Tối thiểu">
                                        <input id="filter-max" class="form-control form-control-sm" placeholder="Tối đa">
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button id="apply-filters" class="btn btn-primary btn-sm">Áp dụng</button>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative" href="cart.php" id="cart-link">
                            <i class="bi bi-cart-fill"></i>
                            <span id="cart-count" class="cart-badge" style="display:none;">0</span>
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link" href="#" id="theme-toggle" title="Dark / Light">
                            <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user'])):
                        $hoTen = htmlspecialchars($_SESSION['user']['ho_ten'] ?? 'Người dùng');
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?php echo $hoTen; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person"></i> Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="don_hang.php"><i class="bi bi-card-list"></i> Đơn hàng</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i></a></li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SLIDER -->
    <div class="container mt-4 mb-4">
        <div class="hero-slider" id="hero-slider">
            <div class="hero-slide active" style="background-image:url('https://cdn.sforum.vn/sforum/wp-content/uploads/2022/11/hinh-nen-may-tinh-world-cup-2022-7.jpg')"></div>
            <div class="hero-slide" style="background-image:url('https://png.pngtree.com/thumb_back/fw800/back_our/20190621/ourmid/pngtree-guild-wars-world-cup-psd-layering-image_192765.jpg')"></div>
            <div class="hero-slide" style="background-image:url('https://presetsandmore.com/wp-content/uploads/2018/06/psd-world-cup.jpg')"></div>
        </div>
    </div>




    <div class="row">
        <div class="col-md-3 mb-3">
            <!-- Filters side -->
            <div class="filters-panel">
                <h6>Bộ lọc</h6>
                <div class="mb-2">
                    <label class="form-label mb-1">Danh mục</label>
                    <select id="side-filter-category" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="1">Giày thể thao</option>
                        <option value="2">Quần áo thể thao</option>
                        <option value="3">Phụ kiện thể thao</option>
                        <option value="4">Dụng cụ thể thao</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label mb-1">Giá</label>
                    <div class="d-flex gap-2">
                        <input id="side-filter-min" class="form-control form-control-sm" placeholder="Tối thiểu">
                        <input id="side-filter-max" class="form-control form-control-sm" placeholder="Tối đa">
                    </div>
                </div>
                <button id="side-apply" class="btn btn-primary btn-sm w-100">Áp dụng</button>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div><strong id="results-count">0</strong> sản phẩm</div>
                <div>
                    <select id="sort-select" class="form-select form-select-sm">
                        <option value="new">Mới nhất</option>
                        <option value="price_asc">Giá: thấp → cao</option>
                        <option value="price_desc">Giá: cao → thấp</option>
                    </select>
                </div>
            </div>

            <!-- Products grid -->
            <div id="products-grid" class="row g-3"></div>

            <!-- Pagination -->
            <nav class="mt-4">
                <ul id="pagination" class="pagination justify-content-center"></ul>
            </nav>
        </div>
    </div>
    </div>

    <!-- QUICK VIEW MODAL -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">
                <div class="row g-0 p-3">
                    <!-- Hình ảnh sản phẩm -->
                    <div class="col-md-6 text-center">
                        <img id="quick-img" src="" alt="Hình sản phẩm" class="img-fluid rounded-3" style="max-height: 400px; object-fit: contain;">
                    </div>

                    <!-- Thông tin sản phẩm -->
                    <div class="col-md-6 d-flex flex-column justify-content-center ps-4">
                        <h4 id="quick-title" class="fw-bold mb-3 text-white"></h4>
                        <p id="quick-price" class="text-danger fw-bold fs-5 mb-3"></p>
                        <p id="quick-desc" class="mb-4 text-white"></p>

                        <div class="d-flex gap-2">
                            <button id="quick-add" class="btn btn-primary flex-grow-1">Thêm vào giỏ</button>
                            <button class="btn btn-outline-light flex-grow-1" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- CHATBOT -->
    <div id="chatbot-toggle" title="Mở chat"><i class="bi bi-chat-dots-fill" style="font-size:20px;"></i></div>
    <div id="chatbot-panel">
        <div style="background:var(--primary); color:#fff; padding:10px; display:flex; align-items:center; justify-content:space-between;">
            <div><i class="bi bi-robot"></i> Tư vấn sản phẩm</div>
            <div><button id="chat-close" class="btn btn-sm btn-light">✖</button></div>
        </div>
        <div style="padding:10px; display:flex; flex-direction:column; height:360px;">
            <div id="chat-history" style="flex:1; overflow:auto; padding:8px; border-radius:6px; background:rgba(0,0,0,0.03)"></div>
            <div class="mt-2 d-flex gap-2">
                <input id="chat-input-panel" class="form-control" placeholder="Ví dụ: giày dưới 2 triệu">
                <button id="chat-send-panel" class="btn btn-primary">Gửi</button>
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div class="app-toast" id="toast-root"></div>

    <!-- flying image -->
    <div id="fly-wrap"></div>

    <!-- FOOTER -->
    <div class="footer text-center mt-4">
        <p>© 2025 SPORTSHOP - Web bán đồ thể thao</p>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        // ---------- DATA ----------
        window._PRODUCTS = <?php echo json_encode($allProducts); ?>;

        // ---------- STATE ----------
        let state = {
            query: '',
            category: '',
            min: null,
            max: null,
            sort: 'new',
            page: parseInt(localStorage.getItem('productsPage')) || 1, // Lấy page từ localStorage nếu có
            perPage: 8,
            cart: JSON.parse(localStorage.getItem('cart') || '[]')
        };


        // ---------- HELPERS ----------
        function $(sel) {
            return document.querySelector(sel);
        }

        function $$(sel) {
            return Array.from(document.querySelectorAll(sel));
        }

        function refreshCartBadge(anim = true) {
            const cnt = state.cart.reduce((s, i) => s + (i.qty || 1), 0);
            const el = $('#cart-count');
            if (cnt > 0) {
                el.style.display = 'inline-block';
                el.textContent = cnt;
                if (anim) {
                    el.animate([{
                        transform: 'scale(1.4)'
                    }, {
                        transform: 'scale(1)'
                    }], {
                        duration: 250
                    });
                }
            } else el.style.display = 'none';
            localStorage.setItem('cart', JSON.stringify(state.cart));
        }

        function toast(msg, type = 'info', timeout = 2500) {
            const root = $('#toast-root');
            const id = 't' + Date.now();
            const el = document.createElement('div');
            el.id = id;
            el.className = 'toast align-items-center';
            el.style.minWidth = '220px';
            el.innerHTML = `<div class="d-flex"><div class="toast-body">${msg}</div><button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
            root.appendChild(el);
            const bs = new bootstrap.Toast(el, {
                delay: timeout
            });
            bs.show();
            el.addEventListener('hidden.bs.toast', () => el.remove());
        }

        function flyToCart(imgSrc, startRect) {
            const fly = document.createElement('img');
            fly.src = imgSrc;
            fly.className = 'flying-img';
            document.body.appendChild(fly);
            fly.style.left = startRect.left + 'px';
            fly.style.top = startRect.top + 'px';
            const cart = $('#cart-link').getBoundingClientRect();
            const dx = cart.left + (cart.width / 2) - (startRect.left + startRect.width / 2);
            const dy = cart.top + (cart.height / 2) - (startRect.top + startRect.height / 2);
            fly.style.transition = 'transform .8s cubic-bezier(.2,.8,.2,1), opacity .8s';
            requestAnimationFrame(() => {
                fly.style.transform = `translate(${dx}px,${dy}px) scale(.2)`;
                fly.style.opacity = '0.3';
            });
            setTimeout(() => {
                fly.remove();
                $('#cart-count').classList.add('cart-badge-ani');
                setTimeout(() => $('#cart-count').classList.remove('cart-badge-ani'), 400);
            }, 900);
        }

        // ---------- RENDER GRID ----------
        const isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;

        function applyFiltersAndRender() {
            const q = state.query.trim().toLowerCase();
            let filtered = window._PRODUCTS.slice();
            if (state.category) filtered = filtered.filter(p => String(p.danh_muc_id) === String(state.category));
            if (state.min != null) filtered = filtered.filter(p => p.gia >= state.min);
            if (state.max != null) filtered = filtered.filter(p => p.gia <= state.max);
            if (q) filtered = filtered.filter(p => (p.ten + ' ' + (p.mo_ta || '')).toLowerCase().includes(q));

            if (state.sort === 'price_asc') filtered.sort((a, b) => a.gia - b.gia);
            else if (state.sort === 'price_desc') filtered.sort((a, b) => b.gia - a.gia);
            else filtered.sort((a, b) => new Date(b.ngay_tao || 0) - new Date(a.ngay_tao || 0));

            const total = filtered.length;
            const totalPages = Math.max(1, Math.ceil(total / state.perPage));
            if (state.page > totalPages) state.page = totalPages;
            const start = (state.page - 1) * state.perPage;
            const pageItems = filtered.slice(start, start + state.perPage);

            $('#results-count').textContent = total;
            const grid = $('#products-grid');
            grid.innerHTML = '';
            if (pageItems.length === 0) grid.innerHTML = '<div class="col-12"><div class="p-4 text-center">Không tìm thấy sản phẩm.</div></div>';
            else pageItems.forEach(p => {
                const col = document.createElement('div');
                col.className = 'col-sm-6 col-md-4 col-lg-3';
                col.innerHTML = `<div class="product-card p-2"><div class="img-wrap"><img src="${p.hinh_anh}" alt="${p.ten}"></div>
        <div class="meta"><div class="d-flex justify-content-between align-items-center"><div class="fw-semibold">${p.ten}</div>
        <div class="text-danger fw-bold">${p.gia.toLocaleString()} đ</div></div>
        <div class="mt-2 d-flex gap-2">
        <button class="btn btn-sm btn-outline-primary quick-view-btn" data-id="${p.id}"><i class="bi bi-eye"></i></button>
        <button class="btn btn-sm btn-primary add-cart-btn" data-id="${p.id}" data-img="${p.hinh_anh}" data-name="${p.ten}" data-price="${p.gia}"><i class="bi bi-bag-plus"></i> Thêm</button>
        </div></div></div>`;
                grid.appendChild(col);
            });

            // pagination
            const ul = $('#pagination');
            ul.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = 'page-item' + (i === state.page ? ' active' : '');
                li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                li.addEventListener('click', e => {
                    e.preventDefault();
                    state.page = i;
                    localStorage.setItem('productsPage', i); // lưu page hiện tại
                    applyFiltersAndRender();
                });

                ul.appendChild(li);
            }

            // thêm sản phẩm vào c
            document.querySelectorAll('.add-cart-btn').forEach(btn => {
                btn.onclick = function() {
                    if (!isLoggedIn) {
                        // Nếu chưa đăng nhập
                        toast('Vui lòng đăng nhập để thêm sản phẩm vào giỏ', 'info', 2000);
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 1500);
                        return;
                    }

                    const id = this.dataset.id;
                    const imgSrc = this.dataset.img;
                    const imgRect = this.getBoundingClientRect();

                    fetch('ajax_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `action=add&product_id=${id}`
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                toast('Đã thêm sản phẩm vào giỏ hàng');
                                flyToCart(imgSrc, imgRect);
                                refreshCartBadge();
                            }
                        });
                }
            });

            $$('.quick-view-btn').forEach(btn => {
                btn.onclick = function() {
                    const p = window._PRODUCTS.find(x => x.id == this.dataset.id);
                    $('#quick-img').src = p.hinh_anh;
                    $('#quick-title').textContent = p.ten;
                    $('#quick-price').textContent = p.gia.toLocaleString() + ' đ';
                    $('#quick-desc').textContent = p.mo_ta || '';
                    const modal = new bootstrap.Modal($('#quickViewModal'));
                    modal.show();

                    $('#quick-add').onclick = function() {
                        if (!isLoggedIn) {
                            toast('Vui lòng đăng nhập để thêm sản phẩm vào giỏ', 'info', 2000);
                            setTimeout(() => {
                                window.location.href = 'login.php';
                            }, 1500);
                            return;
                        }

                        fetch('ajax_cart.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: `action=add&product_id=${p.id}`
                            })
                            .then(res => res.json())
                            .then(res => {
                                if (res.success) {
                                    toast('Đã thêm ' + p.ten + ' vào giỏ hàng');
                                    refreshCartBadge();
                                    const modal = bootstrap.Modal.getInstance($('#quickViewModal'));
                                    modal.hide();
                                }
                            });
                    };
                }
            });

            // ---------- REFRESH CART BADGE ----------
            function refreshCartBadge() {
                fetch('ajax_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'action=count'
                    })
                    .then(res => res.json())
                    .then(res => {
                        const el = document.getElementById('cart-count');
                        if (res.count > 0) {
                            el.style.display = 'inline-block';
                            el.textContent = res.count;
                        } else {
                            el.style.display = 'none';
                        }
                    });
            }

        }

        // ---------- EVENTS ----------
        $('#global-search').addEventListener('input', e => {
            state.query = e.target.value;
            state.page = 1;
            applyFiltersAndRender();
        });
        $('#filter-category').addEventListener('change', e => {
            state.category = e.target.value;
            state.page = 1;
        });
        $('#side-filter-category').addEventListener('change', e => {
            state.category = e.target.value;
            state.page = 1;
        });
        $('#apply-filters').addEventListener('click', () => {
            state.min = parseInt($('#filter-min').value) || null;
            state.max = parseInt($('#filter-max').value) || null;
            applyFiltersAndRender();
        });
        $('#side-apply').addEventListener('click', () => {
            state.min = parseInt($('#side-filter-min').value) || null;
            state.max = parseInt($('#side-filter-max').value) || null;
            applyFiltersAndRender();
        });
        $('#sort-select').addEventListener('change', e => {
            state.sort = e.target.value;
            applyFiltersAndRender();
        });

        // ---------- THEME ----------
        $('#theme-toggle').addEventListener('click', () => {
            const body = document.body;
            if (body.dataset.theme === 'light') {
                body.dataset.theme = 'dark';
                document.cookie = 'theme=dark;path=/';
                $('#theme-icon').className = 'bi bi-sun-fill';
            } else {
                body.dataset.theme = 'light';
                document.cookie = 'theme=light;path=/';
                $('#theme-icon').className = 'bi bi-moon-stars-fill';
            }
        });

        // ---------- CHATBOT ----------
        $('#chatbot-toggle').onclick = () => $('#chatbot-panel').style.display = 'flex';
        $('#chat-close').onclick = () => $('#chatbot-panel').style.display = 'none';
        $('#chat-send-panel').onclick = function() {
            const inp = $('#chat-input-panel');
            const msg = inp.value.trim();
            if (!msg) return;
            const hist = $('#chat-history');
            const userDiv = document.createElement('div');
            userDiv.textContent = 'Bạn: ' + msg;
            userDiv.style.marginBottom = '5px';
            hist.appendChild(userDiv);
            inp.value = '';
            const botDiv = document.createElement('div');
            botDiv.textContent = 'Bot: Xin lỗi, tính năng tư vấn chưa triển khai.';
            botDiv.style.marginBottom = '5px';
            hist.appendChild(botDiv);
            hist.scrollTop = hist.scrollHeight;
        }

        // ---------- HERO SLIDER ----------
        let slideIndex = 0;

        function showSlide() {
            const slides = document.querySelectorAll('.hero-slide');
            slides.forEach(s => s.classList.remove('active'));
            slides[slideIndex].classList.add('active');
            slideIndex = (slideIndex + 1) % slides.length;
        }
        setInterval(showSlide, 4000);
        showSlide();


        // ---------- INIT ----------
        refreshCartBadge();
        applyFiltersAndRender();
    </script>

</body>

</html>