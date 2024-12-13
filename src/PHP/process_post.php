<?php
    session_start();
    require_once '../PHP/config.php';

    function redirect($url, $params = []) {
    $queryString = $params ? '?' . http_build_query($params) : '';
    header("Location: $url$queryString");
    exit();
}


    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        // Lấy dữ liệu từ biểu mẫu
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $content = isset($_POST['content']) ? $_POST['content'] : '';

          // Check if user_id is set
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            // Handle the case where user_id is not set
            echo "<script>alert('Hãy đăng nhập để tạo bài viết .'); window.location.href='../layouts/login.html';</script>";
            exit;
        }
        // Kiểm tra dữ liệu đầu vào
        if (empty($title) || empty($content) ) {
            echo "<script>alert('Vui lòng nhập đầy đủ thông tin!'); window.history.back();</script>";
            exit;
        }
        // Chuẩn bị và thực thi truy vấn
        $stmt = $conn->prepare("INSERT INTO baiviet (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id ,$title, $content);

        if ($stmt->execute()) {
            redirect('../layouts/createpost.php', ['post-success' => 'success']);
        } else {
            redirect('../layouts/createpost.php', ['post-fail' => 'fail']);
        }
        $stmt->close();
    }

    $conn->close();
?>