<?php
    require_once 'config.php';
    session_start();

    $status = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
        $title = $_POST['title'];
        $content = preg_replace('/\s+/', ' ', $_POST['content']);
        $user_id = $_SESSION['user_id'];

        if ($post_id) {
            // Cập nhật bài viết
            $stmt = $conn->prepare("UPDATE baiviet SET title = ?, content = ? WHERE posts_id = ? AND user_id = ?");
            $stmt->bind_param("ssii", $title, $content, $post_id, $user_id);

        if ($stmt->execute()) {
            $status = 'success';
            $redirect_url = "../layouts/view_post.php?id=" . ($post_id ?? $stmt->insert_id);
        } else {
            $status = 'error';
        }
        $stmt->close();
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.js"></script>
    <title>VietTechBlog</title>
</head>
<body>
    <script>
        <?php if ($status === 'success'): ?>
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: 'Bài viết đã được cập nhật!',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "<?php echo $redirect_url; ?>";
            });
        <?php elseif ($status === 'error'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Thất bại',
                text: 'Đã xảy ra lỗi khi cập nhật bài viết!',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>
    </script>
</body>
</html>