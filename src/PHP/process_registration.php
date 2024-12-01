<?php
// Khởi tạo session ngay khi bắt đầu mã PHP
session_start();

// Kết nối cơ sở dữ liệu
include('../PHP/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận dữ liệu từ form
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    
    // Kiểm tra các điều kiện hợp lệ
    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        die("Vui lòng điền đầy đủ thông tin!");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Địa chỉ email không hợp lệ!");
    }

    if (strlen($password) < 8) {
        die("Mật khẩu phải có ít nhất 8 ký tự!");
    }

    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        die("Mật khẩu phải chứa ít nhất một ký tự đặc biệt!");
    }

    if ($password !== $confirmPassword) {
        die("Mật khẩu không khớp!");
    }

    // Kiểm tra xem email hoặc tên đăng nhập đã tồn tại chưa
    $stmt = $conn->prepare("SELECT COUNT(*) FROM taikhoan WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    if ($count > 0) {
        die("Email hoặc tên tài khoản đã tồn tại!");
    }
    $stmt->close();  // Giải phóng kết quả của truy vấn SELECT

    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Chuẩn bị câu lệnh SQL
    $sql = "INSERT INTO taikhoan (fullname, email, username, password, role) VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $role = 0; // Vai trò mặc định là người dùng
        $stmt->bind_param("sssss", $fullname, $email, $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Đăng ký thành công!';
            $stmt->close();  // Giải phóng kết quả của truy vấn INSERT
            header("Location: ../layouts/login.html");
            exit();
        } else {
            $stmt->close();  // Giải phóng kết quả của truy vấn INSERT
            die("Lỗi khi đăng ký: Vui lòng thử lại sau.");
        }
    } else {
        die("Lỗi: Không thể chuẩn bị truy vấn SQL!");
    }
}

// Đóng kết nối
$conn->close();
?>
