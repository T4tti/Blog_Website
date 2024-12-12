<?php
    session_start();
    require_once '../PHP/config.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        // Lấy dữ liệu từ biểu mẫu
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $content = isset($_POST['content']) ? $_POST['content'] : '';

        $user_id = $_SESSION['user_id'];
        // Kiểm tra dữ liệu đầu vào
        if (empty($title) || empty($content) ) {
            echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
            exit;
        }
        // Chuẩn bị và thực thi truy vấn
        $stmt = $conn->prepare("INSERT INTO baiviet (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Bài viết đã được lưu thành công!'); window.location.href='../PHP/post.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra khi lưu bài viết!'); window.history.back();</script>";
        }
        $stmt->store_result();
    }
        $stmt->close();
        $conn->close();
?>