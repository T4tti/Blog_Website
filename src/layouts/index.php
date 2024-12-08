<?php
session_start();

?>


<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VietTechBlog</title>
  <link rel="icon" type="img/png" href="../Assets/favicon-32x32.png" sizes="favicon-32x32" />
  <link rel="stylesheet" href="../CSS/index.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
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

        <form class="d-flex me-3">
          <div class="search-form">
            <input class="form-control" type="search" placeholder="Tìm kiếm..." aria-label="Search"
              style="width: 300px" />
            <button class="btn btn-primary" type="submit">
              <img src="../Assets/icons8-search-20.png" alt="Search" />
            </button>
          </div>
        </form>

        <!-- Kiểm tra trạng thái đăng nhập -->
        <?php if (isset($_SESSION['username'])): ?>
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
        <?php else: ?>
        <a class="nav-link" href="login.html" id="btn-login">
          <img src="../Assets/icons8-login-24.png" alt="Login" class="me-2" />
          Đăng nhập/Đăng ký
        </a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <!-- Banner -->
  <section class="banner">
    <img src="../Assets/000.png" alt="Hero Image" class="img-fluid" />
  </section>

  <!-- Featured Posts -->
  <section class="featured-posts">
    <div class="container">
      <h2 class="text-center mb-5">Bài Viết Nổi Bật</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card post-card">
            <img src="../Assets/Tech_trend.webp" alt="Post thumbnail" />
            <div class="card-body">
              <h5 class="card-title">Xu hướng công nghệ AI </h5>
              <p class="card-text">
                Khám phá những xu hướng công nghệ mới nhất đang định hình
                tương lai.
              </p>
              <a href="view_post.php?id=3" class="btn btn-primary">Đọc thêm</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card post-card">
            <img src="../Assets/AI_python.jpg" alt="Post thumbnail" />
            <div class="card-body">
              <h5 class="card-title">Lập trình AI với Python</h5>
              <p class="card-text">
                Hướng dẫn chi tiết về việc xây dựng các mô hình AI đơn giản.
              </p>
              <a href="view_post.php?id=4" class="btn btn-primary">Đọc thêm</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card post-card">
            <img src="../Assets/front-end.jpeg" alt="Post thumbnail" />
            <div class="card-body">
              <h5 class="card-title">Web Development 2024</h5>
              <p class="card-text">
                Những công nghệ và framework web phổ biến nhất năm 2024.
              </p>
              <a href="view_post.php?id=5" class="btn btn-primary">Đọc thêm</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

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
              <a href="../layouts/post.php" class="text-light-emphasis text-decoration-none hover-white">Bài viết mới</a>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>