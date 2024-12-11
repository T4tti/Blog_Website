<?php
require_once '../PHP/config.php';

// Kiểm tra nếu có tham số id được gửi qua URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Truy vấn bài viết theo id
    $stmt = $conn->prepare("SELECT p.title, p.content, p.created_at, a.fullname 
                            FROM baiviet p
                            JOIN taikhoan a ON p.user_id = a.id
                            WHERE p.posts_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($title, $content, $created_at, $fullname);

    if ($stmt->fetch()) {
        // Dữ liệu bài viết đã được lấy thành công
    } else {
        die("Bài viết không tồn tại.");
    }

    $stmt->close();
} else {
    die("Thiếu ID bài viết.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($title); ?>
    </title>
    <link rel="icon" type="img/png" href="../Assets/favicon-32x32.png" sizes="favicon-32x32" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/view_post.css">
</head>

<body>
    <!-- Navbar -->
    <?php include '../layouts/navbar.php'; ?>

    <div class="container">
        <div class="post">
            <h1>
                <?php echo htmlspecialchars($title); ?>
            </h1>
            <p class="meta">
                <strong>Tác giả:</strong>
                <?php echo htmlspecialchars($fullname); ?> |
                <em>Ngày đăng:</em>
                <?php echo date("h:i A, d/m/Y", strtotime($created_at)); ?>
            </p>
            <div>
                <?php echo nl2br($content); ?>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <?php include '../layouts/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>