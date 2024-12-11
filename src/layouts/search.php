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
    <?php include '../layouts/navbar.php'; ?>

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
    <?php include '../layouts/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>