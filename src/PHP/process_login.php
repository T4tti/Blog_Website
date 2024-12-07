<?php
session_start();
require_once '../PHP/config.php';

function sanitize_input($data) {
    return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8');
}

function redirect($url, $params = []) {
    $queryString = $params ? '?' . http_build_query($params) : '';
    header("Location: $url$queryString");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username_or_email = sanitize_input($_POST['username']);
        $password = $_POST['password'];

        // Check for empty fields
        if (empty($username_or_email) || empty($password)) {
            redirect('../layouts/login.html', ['error' => 'Vui lòng điền đầy đủ thông tin']);
        }

        // Prepare and execute query
        $stmt = $conn->prepare("SELECT fullname, username, email, birth, gender, password, role FROM taikhoan WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['birth'] = $user['birth'];
                $_SESSION['gender'] = $user['gender'];
                $_SESSION['last_login'] = time();
                
                redirect('../layouts/index.php');
            } else {
                redirect('../layouts/login.html', ['pwd-error' => 'Mật khẩu không chính xác']);
            }
        } else {
            redirect('../layouts/login.html', ['user-error' => 'Tài khoản không tồn tại']);
        }
    } catch (Exception $e) {
        error_log("Lỗi đăng nhập: " . $e->getMessage());
        redirect('../layouts/login.html', ['error' => 'Đã xảy ra lỗi, vui lòng thử lại']);
    } finally {
        $conn->close();
    }
}
?>
