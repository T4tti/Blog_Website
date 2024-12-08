<?php
session_start();

require_once '../PHP/config.php';

$limit = 10; // Số bài viết trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("
    SELECT p.posts_id, p.title, p.created_at, a.fullname
    FROM baiviet p
    JOIN taikhoan a ON p.user_id = a.id
    ORDER BY p.created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VietTechBlog - Cộng đồng chia sẻ kiến thức công nghệ, lập trình và AI.">
    <meta name="keywords" content="Công nghệ, Lập trình, AI, Machine Learning, Blog">
    <meta name="author" content="VietTechBlog Team">
    <title>Danh Sách Bài Viết - VietTechBlog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/post.css">
   <link rel="icon" type="img/png" href="../Assets/favicon-32x32.png" sizes="favicon-32x32" />
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
                    <li class="nav-item">
                        <a class="nav-link" href="../layouts/post.php">Bài Viết</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="question">Hỏi Đáp</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="discussion">Thảo Luận</a>
                    </li>
                </ul>
                <form class="d-flex me-3">
                    <div class="search-form">
                        <input class="form-control" type="search" placeholder="Tìm kiếm..." aria-label="Search"
                            style="width: 300px" />
                        <button class="btn btn-primary" type="submit">
                            <img src="../Assets/icons8-search-20.png" alt="Search" />
                        </button>
                    </div>
                </form>

                <!-- Kiểm tra trạng thái đăng nhập -->
                <?php if (isset($_SESSION['username'])): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="../Assets/icons8-avatar-24.png" alt="User" class="me-2" />
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="../layouts/profile.php">Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item" href="../layouts/createarticle.php">Viết bài</a></li>
                        <li><a class="dropdown-item text-danger" href="../PHP/logout.php">Đăng xuất</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a class="nav-link" href="login.html" id="btn-login">
                    <img src="../Assets/icons8-login-24.png" alt="Login" class="me-2" />
                    Đăng nhập/Đăng ký
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <h2>Danh Sách Bài Viết</h2>
        <?php if (empty($posts)): ?>
            <p>Không có bài viết nào.</p>
        <?php endif; ?>
        <?php foreach ($posts as $post): ?>
            <div class="post-container border rounded mb-3">
                <div class="post-content">
                    <p class="post-meta">
                        <img src="../Assets/icons8-avatar-20.png" alt="Avatar" class="avatar" />
                        <span id="author"><?php echo htmlspecialchars($post['fullname']); ?></span>
                        <span id="date"><?php echo date("h:i A, d/m/Y", strtotime($post['created_at'])); ?></span>
                    </p>

                    <h5>
                        <a class="title" href="view_post.php?id=<?php echo $post['posts_id']; ?>">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                    </h5>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>

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
    </main>

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
                        <li><a href="#" class="text-light-emphasis text-decoration-none hover-white">Bài viết mới</a>
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
                            <a href="#" class="text-white text-decoration-none hover-white">
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