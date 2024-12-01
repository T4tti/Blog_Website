<?php
session_start();
require_once 'config.php'; // Tệp cấu hình kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kiểm tra đầu vào
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Tên người dùng và mật khẩu là bắt buộc.';
        header('Location: login.php');
        exit();
    }

    // Chuẩn bị câu lệnh truy vấn để lấy thông tin tài khoản
    $stmt = $conn->prepare("SELECT id, fullname, username, password, role FROM taikhoan WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công, lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role']; // Lưu vai trò (role)

            // Điều hướng đến trang dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = 'Mật khẩu không chính xác.';
        }
    } else {
        $_SESSION['error'] = 'Tên người dùng hoặc email không tồn tại.';
    }

    $stmt->close();
    $conn->close();

    // Quay lại trang đăng nhập với thông báo lỗi
    header('Location: login.php');
    exit();
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Phương thức không được phép.';
    exit();
}
?>
