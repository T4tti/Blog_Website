<?php
session_start();

// Kết nối cơ sở dữ liệu
require_once '../PHP/config.php';

// Hàm xử lý đầu vào để ngăn chặn XSS
function sanitize_input($data) {
    return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = $_SESSION['username'];

        if (isset($_POST['submit_info'])) {
            $fullname = sanitize_input($_POST['fullname']);
            $birth = sanitize_input($_POST['birth']);
            $gender = sanitize_input($_POST['gender']);

            // Kiểm tra thông tin không được để trống
            if (empty($fullname) || empty($birth) || empty($gender)) {
                throw new Exception("Vui lòng điền đầy đủ thông tin.");
            }

            // Kiểm tra tuổi >= 16
            $birthYear = (int) date('Y', strtotime($birth));
            $currentYear = (int) date('Y');
            if ($currentYear - $birthYear < 13) {
                throw new Exception("Bạn phải từ 13 tuổi trở lên.");
            }

            // Cập nhật thông tin cá nhân
            $stmt = $conn->prepare("UPDATE taikhoan SET fullname = ?, birth = ?, gender = ? WHERE username = ?");
            $stmt->bind_param("ssss", $fullname, $birth, $gender, $username);
            $stmt->execute();

            // Cập nhật session
            $_SESSION['fullname'] = $fullname;
            $_SESSION['birth'] = $birth;
            $_SESSION['gender'] = $gender;

            $_SESSION['flash_success'] = "Cập nhật thông tin thành công!";
        } elseif (isset($_POST['submit_password'])) {          // Nhận và làm sạch dữ liệu từ form
            $new_password = sanitize_input($_POST['new_password']);
            $confirm_password = sanitize_input($_POST['confirm_password']);
            
            // Kiểm tra nếu forget=true từ URL
            $forget = isset($_GET['forget']) && $_GET['forget'] === 'true';

            // Nếu không phải quên mật khẩu, cần lấy mật khẩu hiện tại
            if (!$forget) {
                $current_password = sanitize_input($_POST['current_password']);
                
                // Kiểm tra thông tin mật khẩu không được để trống
                if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                    throw new Exception("Vui lòng điền đầy đủ thông tin mật khẩu.");
                }
            } else {
                // Nếu là quên mật khẩu, chỉ cần kiểm tra mật khẩu mới và xác nhận
                if (empty($new_password) || empty($confirm_password)) {
                    throw new Exception("Vui lòng nhập mật khẩu mới và xác nhận mật khẩu.");
                }
                $stmt = $conn->prepare("SELECT username FROM taikhoan WHERE email = ?");
                $stmt->bind_param("s", $_SESSION['email']);
                $stmt->execute();
                $stmt->bind_result($username);
                $stmt->fetch();
                $stmt->close();
                $_SESSION['email'] = '';    // Xóa email khỏi session
            }

            // Kiểm tra mật khẩu xác nhận
            if ($new_password !== $confirm_password) {
                throw new Exception("Mật khẩu mới và xác nhận không khớp.");
            }

            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/', $new_password)) {
                throw new Exception("Mật khẩu phải ít nhất 8 ký tự, bao gồm chữ cái, số và ít nhất một ký tự đặc biệt.");
            }

            // Nếu không phải quên mật khẩu, kiểm tra mật khẩu hiện tại
            if (!$forget) {
                $stmt = $conn->prepare("SELECT password FROM taikhoan WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($hashed_password);
                $stmt->fetch();
                $stmt->close();

                if (!password_verify($current_password, $hashed_password)) {
                    throw new Exception("Mật khẩu hiện tại không chính xác.");
                }
            }

            // Cập nhật mật khẩu mới
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE taikhoan SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $new_hashed_password, $username);
            $stmt->execute();
            $stmt->close();

            $_SESSION['flash_success'] = "Đổi mật khẩu thành công!";
            header("Location: ../layouts/login.html");
        }
    } catch (Exception $e) {
        $_SESSION['flash_error'] = $e->getMessage();
    }

    header("Location: " . $_SERVER['PHP_SELF']);

    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VietTechBlog Accounts</title>
    <link rel="icon" type="image/png" href="../Assets/favicon-32x32.png" sizes="32x32" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../CSS/personal.css">
</head>

<body class="bg-light">
    <div class="background"></div>
    <?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="container profile-container">
        <h2 class="text-center">Thông Tin Cá Nhân</h2>
        <p class="text-center text-muted">Quản lý thông tin tài khoản của bạn.</p>
        <ul class="nav nav-tabs mb-4" id="accountTabs" role="tablist">
            <li class="nav-item">
                <button
                    class="nav-link <?php echo (!isset($_GET['tab']) || $_GET['tab'] !== 'password') ? 'active' : ''; ?>"
                    id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">Thông Tin Cá Nhân</button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'password') ? 'active' : ''; ?>"
                    id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button">Đổi Mật
                    Khẩu</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade <?php echo (!isset($_GET['tab']) || $_GET['tab'] !== 'password') ? 'show active' : ''; ?>"
                id="info">
                <form method="POST">
                    <div class="mb-3">
                        <label for="fullname">Tên hiển thị</label>
                        <input type="text" name="fullname" id="fullname" class="form-control"
                            value="<?php echo $_SESSION['fullname'] ?? ''; ?>" required minlength="3" maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="birth">Ngày sinh</label>
                        <input type="date" name="birth" id="birth" class="form-control" min="1900-01-01"
                            max="<?php echo date('Y-m-d', strtotime('-16 years')); ?>"
                            value="<?php echo $_SESSION['birth'] ?? ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender">Giới tính</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="" <?php echo empty($_SESSION['gender']) ? 'selected' : '' ; ?>>Chọn</option>
                            <option value="Nam" <?php echo $_SESSION['gender']==='Nam' ? 'selected' : '' ; ?>>Nam
                            </option>
                            <option value="Nữ" <?php echo $_SESSION['gender']==='Nữ' ? 'selected' : '' ; ?>>Nữ</option>
                            <option value="Khác" <?php echo $_SESSION['gender']==='Khác' ? 'selected' : '' ; ?>>Khác
                            </option>
                        </select>
                    </div>
                    <button type="submit" name="submit_info" class="btn btn-success">Cập nhật</button>
                    <button type="button" class="btn btn-danger"
                        onclick=" window.location.href = '../layouts/profile.php'">Hủy bỏ</button>
                </form>
            </div>

            <div class="tab-pane fade <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'password') ? 'show active' : ''; ?>"
                id="password">
                <form method="POST" id="frm-pwd">
                    <?php if (!isset($_GET['forget']) || $_GET['forget'] !== 'true'): ?>
                    <div class="mb-3">
                        <label for="current_password">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" id="current_password" class="form-control">
                        <div id="error-cur" class="custom-error" role="alert" aria-live="polite" style="display: none;">
                            Mật khẩu hiện tại là bắt buộc
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="new_password">Mật khẩu mới</label>
                        <input type="password" name="new_password" id="new_password" class="form-control">
                        <div id="error-new" class="custom-error" role="alert" aria-live="polite" style="display: none;">
                            Mật khẩu mới là bắt buộc
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password">Xác nhận mật khẩu</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                        <div id="error-conf" class="custom-error" role="alert" aria-live="polite"
                            style="display: none;">
                            Xác nhận mật khẩu là bắt buộc
                        </div>
                    </div>

                    <button type="submit" name="submit_password" class="btn btn-primary">Đổi mật khẩu</button>
                    <button type="button" class="btn btn-danger"
                        onclick="window.location.href = '../layouts/profile.php'">Hủy bỏ</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/personal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>                   
</body>

</html>