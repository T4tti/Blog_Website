<?php
// admin.php
session_start();

require_once '../PHP/config.php';


// Kiểm tra xem người dùng có phải admin không
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../layouts/login.html?error=access_denied");
    exit();
}


// Xóa bài viết
if (isset($_POST['delete_post'])) {
    $post_id = $_POST['post_id'];
    $conn->query("DELETE FROM baiviet WHERE id = $post_id");
}

// Xóa tài khoản (trừ admin)
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $conn->query("DELETE FROM taikhoan WHERE id = $user_id AND role != 1");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <!-- Thêm Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Thêm CSS -->
  <link rel="stylesheet" href="../CSS/admin.css">
</head>

<body>
  <!-- Navbar -->
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="../Assets/android-icon-48x48.png" alt="Logo" class="me-2" />
        VietTechBlog
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="../layouts/post.php">Bài Viết</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="question">Hỏi Đáp</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="discussion">Thảo Luận</a>
          </li>
        </ul>

        <form id="frm-search" class="d-flex me-3" action="../layouts/search.php" method="post">
          <div class="search-form">
            <input class="form-control" type="search" placeholder="Tìm kiếm..." aria-label="Search" name="query"
              id="query" style="width: 300px" />
            <button class="btn btn-primary" type="submit">
              <img src="../Assets/icons8-search-20.png" alt="Search" />
            </button>
          </div>
        </form>

        <!-- Kiểm tra trạng thái đăng nhập -->
        <?php if (isset($_SESSION['username']) &&  $_SESSION['role'] == 0): ?>
        <div class="dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown"
            aria-expanded="false">
            <img src="../Assets/icons8-avatar-24.png" alt="User" class="me-2" />
            <?php echo htmlspecialchars($_SESSION['username']); ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li><a class="dropdown-item" href="../layouts/profile.php">Hồ sơ cá nhân</a></li>
            <li><a class="dropdown-item" href="../layouts/createarticle.php">Viết bài</a></li>
            <li><a class="dropdown-item text-danger" href="../PHP/logout.php">Đăng xuất</a></li>
          </ul>
        </div>
        <?php elseif (isset($_SESSION['username']) &&  $_SESSION['role'] == 1):?>
        <div class="dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown"
            aria-expanded="false">
            <img src="../Assets/icons8-avatar-24.png" alt="User" class="me-2" />
            <?php echo htmlspecialchars($_SESSION['username']); ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li><a class="dropdown-item" href="../layouts/profile.php">Hồ sơ cá nhân</a></li>
            <li><a class="dropdown-item" href="../layouts/createarticle.php">Viết bài</a></li>
            <li><a class="dropdown-item" href="../layouts/admin.php">Quản trị</a></li>
            <li><a class="dropdown-item text-danger" href="../PHP/logout.php">Đăng xuất</a></li>
          </ul>
        </div>
        <?php else: ?>
        <a class="nav-link" href="login.html" id="btn-login">
          <img src="../Assets/icons8-login-24.png" alt="Login" class="me-2" />
          Đăng nhập/Đăng ký
        </a>
        <?php endif; ?>
      </div>
    </div>
  </nav>


  <div class="container my-5">
    <h1 class="text-center mb-4">Quản lý Admin</h1>

    <!-- Danh sách bài viết -->
    <h2 class="mt-4">Danh sách Bài viết</h2>
    <div class="table-container" style="max-height: 400px; overflow-y: auto;">
      <table class="table table-striped table-hover custom-table">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Người đăng</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php
      $result = $conn->query("SELECT b.posts_id, b.title, a.username FROM baiviet b JOIN taikhoan a ON b.user_id = a.id");
      while ($row = $result->fetch_assoc()):
      ?>
          <tr>
            <td>
              <?php echo $row['posts_id']; ?>
            </td>
            <td class="scrollable-td">
                <a class="title" href="../layouts/view_post.php?id=<?php echo $row['posts_id']; ?>">
                  <?php echo $row['title']; ?>
                </a>
            </td>
            <td class="scrollable-td">
              <?php echo $row['username']; ?>
            </td>
            <td>
              <form method="POST">
                <input type="hidden" name="post_id" value="<?php echo $row['posts_id']; ?>">
                <button type="submit" name="delete_post" class="btn btn-danger btn-sm">Xóa</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Danh sách tài khoản -->
    <h2 class="mt-4">Danh sách Tài khoản</h2>
    <div class="table-container" style="max-height: 400px; overflow-y: auto;">
      <table class="table table-striped table-hover custom-table">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Tên người dùng</th>
            <th>Vai trò</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $result = $conn->query("SELECT a.id, a.fullname, a.username, r.role_name FROM taikhoan a JOIN quyen r ON a.role = r.role_id");
            while ($row = $result->fetch_assoc()):
          ?>
          <tr>
            <td>
              <?php echo $row['id']; ?>
            </td>
            <td class="scrollable-td">
              <?php echo $row['username']; ?>
            </td>
            <td class="scrollable-td">
              <?php echo $row['role_name']; ?>
            </td>
            <td>
              <?php if ($row['role_name'] != 'admin'): ?>
              <form method="POST">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Xóa</button>
              </form>
              <?php else: ?>
              <span class="badge bg-success">Admin</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <br>
  <br>
  <br>
  <br>

  <!-- Footer -->
  <footer class="footer bg text-light">
    <div class="container py-5">
      <!-- Main Footer Content -->
      <div class="row">
        <!-- About Section -->
        <div class="col-lg-4 mb-4">
          <div class="footer-brand mb-4">
            <img src="../Assets/android-icon-48x48.png" alt="Logo" class="me-2" />
            <span class="fs-4 fw-bold">VietTechBlog</span>
          </div>
          <div class="social-links mt-3">
            <a href="#" class="text-light me-3 text-decoration-none" style="margin-bottom: 30">
              <img src="../Assets/facebook-color-svgrepo-com.svg" alt="Facebook" class="me-1"
                style="width: 5%; height: 5%" />
              Facebook
            </a>
            <a href="#" class="text-light me-3 text-decoration-none">
              <img src="../Assets/twitter-svgrepo-com.svg" alt="Twitter" class="me-1" style="width: 5%; height: 5%" />
              Twitter
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-4 mb-4">
          <h5 class="mb-4 text-white">Khám Phá</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <a href="../layouts/post.php" class="text-light-emphasis text-decoration-none hover-white">Bài viết
                mới</a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">Chủ đề hot</a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">Series bài viết</a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">Tác giả tiêu biểu</a>
            </li>
          </ul>
        </div>

        <!-- Categories -->
        <div class="col-lg-2 col-md-4 mb-4">
          <h5 class="mb-4 text-white">Chủ Đề</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">Lập trình</a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">AI & Machine Learning</a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">Web Development</a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">Mobile Development</a>
            </li>
          </ul>
        </div>

        <!-- Support & Newsletter -->
        <div class="col-lg-4 col-md-4 mb-4">
          <h5 class="mb-3 text-white">Hỗ Trợ</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">
                Trung tâm trợ giúp
              </a>
            </li>
            <li class="mb-2">
              <a href="#" class="text-light-emphasis text-decoration-none hover-white">
                Liên hệ hỗ trợ
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Divider -->
      <hr class="mt-4 mb-4 border-secondary" />

      <!-- Bottom Footer -->
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          <p class="mb-0 text-white">
            &copy; 2024 VietTechBlog. All rights reserved.
          </p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <ul class="list-inline mb-0">
            <li class="list-inline-item">
              <a href="../layouts/terms.html" class="text-white text-decoration-none hover-white">
                Điều khoản sử dụng
              </a>
            </li>
            <li class="list-inline-item ms-3">
              <a href="#" class="text-white text-decoration-none hover-white">
                Chính sách bảo mật
              </a>
            </li>
            <li class="list-inline-item ms-3">
              <a href="#" class="text-white text-decoration-none hover-white">
                Cookie Policy
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- Thêm Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conn->close(); ?>