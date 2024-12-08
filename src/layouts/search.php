<?php
// Khởi động session
session_start();
require_once '../PHP/config.php';

// Khởi tạo biến cần thiết
$posts = [];
$limit = 10; // Số bài viết trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy truy vấn tìm kiếm từ POST hoặc GET
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $_SESSION['search_query'] = trim($_POST['query']);
}
$searchQuery = isset($_SESSION['search_query']) ? $_SESSION['search_query'] : '';

// Xử lý tìm kiếm nếu có truy vấn
if (!empty($searchQuery)) {
    $likeSearch = "%$searchQuery%";

    // Truy vấn bài viết
    $sql = "SELECT p.posts_id, p.title, p.created_at, a.fullname 
            FROM baiviet p 
            JOIN taikhoan a ON p.user_id = a.id 
            WHERE p.title LIKE ? OR p.content LIKE ? OR a.username LIKE ? 
            ORDER BY p.created_at DESC 
            LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssii", $likeSearch, $likeSearch, $likeSearch, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

// Lấy tổng số bài viết để phân trang
$total_posts = 0;
if (!empty($searchQuery)) {
    $count_sql = "SELECT COUNT(*) AS total FROM baiviet WHERE title LIKE ? OR content LIKE ?";
    $stmt_count = $conn->prepare($count_sql);
    if ($stmt_count) {
        $stmt_count->bind_param("ss", $likeSearch, $likeSearch);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $total_posts = $result_count->fetch_assoc()['total'];
        $stmt_count->close();
    }
}
$total_pages = ceil($total_posts / $limit);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm | VietTechBlog</title>
    <link rel="icon" type="img/png" href="../Assets/favicon-32x32.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/search.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="../Assets/android-icon-48x48.png" alt="Logo" class="me-2" />
                VietTechBlog
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="../layouts/post.php">Bài Viết</a></li>
                    <li class="nav-item"><a class="nav-link" href="question">Hỏi Đáp</a></li>
                    <li class="nav-item"><a class="nav-link" href="discussion">Thảo Luận</a></li>
                </ul>
                <form class="d-flex me-3" action="../layouts/search.php" method="post">
                    <input class="form-control me-2" type="search" placeholder="Tìm kiếm..." name="query"
                        value="<?php echo htmlspecialchars($searchQuery); ?>" style="width: 300px" />
                    <button class="btn btn-primary" type="submit">
                        <img src="../Assets/icons8-search-20.png" alt="Search" />
                    </button>
                </form>
                <!-- Hiển thị tài khoản -->
                <?php if (isset($_SESSION['username'])): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <img src="../Assets/icons8-avatar-24.png" alt="User" class="me-2" />
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../layouts/profile.php">Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item" href="../layouts/createarticle.php">Viết bài</a></li>
                        <li><a class="dropdown-item text-danger" href="../PHP/logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a class="nav-link" href="login.html">
                    <img src="../Assets/icons8-login-24.png" alt="Login" class="me-2" />
                    Đăng nhập/Đăng ký
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Kết quả tìm kiếm -->
    <div class="container mt-4">
        <h2>Kết quả tìm kiếm</h2>
        <div>
            <?php if (empty($posts)): ?>
            <p>Không tìm thấy kết quả nào phù hợp</p>
            <?php else: ?>
            <?php foreach ($posts as $post): ?>
            <div class="post-container border rounded mb-3 p-3">
                <div class="post-meta mb-2">
                    <img src="../Assets/icons8-avatar-20.png" alt="Avatar" class="avatar" />
                    <span id="author">
                        <?php echo htmlspecialchars($post['fullname']); ?>
                    </span>
                    <span id="date">
                        <?php echo date("h:i A, d/m/Y", strtotime($post['created_at'])); ?>
                    </span>
                </div>
                <h5>
                    <a class="title" href="view_post.php?id=<?php echo $post['posts_id']; ?>">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                </h5>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
 
        <!-- Phân trang -->
        <?php
        // Gọi mã phân trang
        $total_posts_query = $conn->query("SELECT COUNT(*) AS total FROM baiviet");
        $total_posts = $total_posts_query->fetch_assoc()['total'];
        $total_pages = ceil($total_posts / $limit);

        echo '<nav class="mt-4">';
        echo '<ul class="pagination justify-content-center">';
        if ($page > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">&laquo; Trang trước</a></li>';
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
        if ($page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Trang sau &raquo;</a></li>';
        }
        echo '</ul>';
        echo '</nav>';
        ?>
    </div>
  
    <!-- Footer -->
    <footer class="footer bg text-light mt-5">
        <div class="container py-5">
            <div class="row text-center text-md-start">
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand d-flex align-items-center justify-content-center justify-content-md-start">
                        <img src="../Assets/android-icon-48x48.png" alt="Logo" class="me-2" />
                        <span class="fs-4 fw-bold">VietTechBlog</span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="mb-4 text-white">Khám Phá</h5>
                    <ul class="list-unstyled">
                        <li><a href="../layouts/post.php"
                                class="text-light-emphasis text-decoration-none hover-white">Bài viết mới</a>
                        </li>
                        <li><a href="#" class="text-light-emphasis text-decoration-none hover-white">Chủ đề hot</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="mb-4 text-white">Chủ Đề</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light-emphasis text-decoration-none hover-white">Lập trình</a></li>
                        <li><a href="#" class="text-light-emphasis text-decoration-none hover-white">AI & Machine
                                Learning</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 mb-4">
                    <h5 class="mb-3 text-white">Hỗ Trợ</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light-emphasis text-decoration-none hover-white">Trung tâm trợ
                                giúp</a></li>
                    </ul>
                </div>
            </div>
            <!-- Divider -->
            <hr class="mt-4 mb-4 border-secondary" />

            <!-- Bottom Footer -->
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 text-white">
                        &copy; 2024 VietTechBlog. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="../layouts/terms.html" class="text-white text-decoration-none hover-white">
                                Điều khoản sử dụng
                            </a>
                        </li>
                        <li class="list-inline-item ms-3">
                            <a href="#" class="text-white text-decoration-none hover-white">
                                Chính sách bảo mật
                            </a>
                        </li>
                        <li class="list-inline-item ms-3">
                            <a href="#" class="text-white text-decoration-none hover-white">
                                Cookie Policy
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>