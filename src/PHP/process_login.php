<?php
session_start();
require_once '../PHP/config.php'; // Giả sử bạn có file kết nối CSDL này

// Hàm kiểm tra và làm sạch dữ liệu đầu vào
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Kiểm tra nếu form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy và làm sạch dữ liệu từ form
    $username_or_email = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    try {
        // Chuẩn bị câu truy vấn để tìm người dùng
        $stmt = $conn->prepare("SELECT * FROM taikhoan WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kiểm tra xem người dùng có tồn tại không
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password'])) {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Chuyển hướng đến trang chính hoặc trang dashboard
                header("Location: ../layouts/index.html");
                $stmt->close();
                exit();
            } else {
                // Mật khẩu không đúng
                $_SESSION['login_error'] = "Tên người dùng hoặc mật khẩu không chính xác";
                $stmt->close();
                header("Location: ../layouts/login.html");
                exit();
            }
        } else {
            // Không tìm thấy người dùng
            $_SESSION['login_error'] = "Tên người dùng hoặc email không tồn tại";
            $stmt->close();
            header("Location: ../layouts/login.html");
            exit();
        }
    } catch (Exception $e) {
        // Xử lý lỗi
        error_log("Lỗi đăng nhập: " . $e->getMessage());
        $_SESSION['login_error'] = "Đã xảy ra lỗi. Vui lòng thử lại sau.";
        header("Location: ../layouts/login.html");
        exit();
    }
} else {
    // Nếu truy cập trực tiếp vào file mà không phải từ form submit
    header("Location: ../layouts/login.html");
    exit();
}