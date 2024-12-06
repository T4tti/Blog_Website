<?php
session_start();

// Hủy tất cả các biến phiên
$_SESSION = [];

// Hủy cookie phiên (nếu có)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hủy phiên
session_destroy();

// Chuyển hướng người dùng đến trang đăng nhập
header("Location: ../layouts/index.php?logout=success");
exit();
?>
