<?php
session_start();
require_once '../PHP/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../layouts/login.html?error=access_denied");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];

    // Kiểm tra xem bài viết có thuộc về người dùng không
    $stmt = $conn->prepare("
        SELECT posts_id 
        FROM baiviet 
        WHERE posts_id = ? 
        AND user_id = (SELECT id FROM taikhoan WHERE username = ?)
    ");
    $stmt->bind_param("is", $post_id, $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Xóa bài viết
        $delete_stmt = $conn->prepare("DELETE FROM baiviet WHERE posts_id = ?");
        $delete_stmt->bind_param("i", $post_id);
        if ($delete_stmt->execute()) {
            $delete_stmt->close();
            header("Location: ../layouts/profile.php?message=deleted");
            exit();
        } else {
            $delete_stmt->close();
            header("Location: ../layouts/profile.php?message=delete_failed");
            exit();
        }
    } else {
        $stmt->close();
        header("Location: ../layouts/profile.php?message=not_authorized");
        exit();
    }
} else {
    header("Location: ../layouts/profile.php?message=invalid_request");
    exit();
}
?>
