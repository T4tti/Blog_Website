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
    <?php include '../layouts/navbar.php'; ?>

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

    <!-- Footer -->
    <?php include '../layouts/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>