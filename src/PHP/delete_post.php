<?php
session_start();
require_once '../PHP/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../layouts/login.html?error=access_denied");
    exit();
}

if (isset($_GET['id'])) {
    $post_id = (int)$_GET['id'];

    // Kiểm tra xem bài viết có thuộc về người dùng hay không
    $stmt = $conn->prepare("SELECT * FROM baiviet WHERE posts_id = ? AND user_id = (SELECT id FROM taikhoan WHERE username = ?)");
    $stmt->bind_param("is", $post_id, $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Xóa bài viết
        $delete_stmt = $conn->prepare("DELETE FROM baiviet WHERE posts_id = ?");
        $delete_stmt->bind_param("i", $post_id);
        if ($delete_stmt->execute()) {
            header("Location: ../layouts/profile.php?message=deleted");
            exit();
        } else {
            echo "Xóa bài viết thất bại.";
        }
        $delete_stmt->close();
    } else {
        echo "Bạn không có quyền xóa bài viết này.";
    }
    $stmt->close();
} else {
    echo "ID bài viết không hợp lệ.";
}
?>
