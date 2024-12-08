<?php
session_start();

require_once '../PHP/config.php';

// Kiểm tra nếu chưa đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ../layouts/login.html?error=access_denied");
    exit();
}

// Lấy thông tin bài viết của người dùng bằng username
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$stmt = $conn->prepare("
    SELECT p.posts_id, p.title, p.created_at, a.fullname
    FROM baiviet p
    JOIN taikhoan a ON p.user_id = a.id
    WHERE a.username = ?
    ORDER BY p.created_at DESC
    LIMIT ? OFFSET ?
");

$stmt->bind_param("sii", $_SESSION['username'], $limit, $offset);

$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>


<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile | VietTechBlog</title>
  <link rel="icon" type="image/png" href="../Assets/favicon-32x32.png" sizes="32x32" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="../CSS/profile.css" />
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="../Assets/android-icon-48x48.png" alt="Logo VietTechBlog" class="me-2" loading="lazy" />
        VietTechBlog
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
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
        <!-- Dropdown hiển thị thông tin và Logout -->
        <div class="dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" data-bs-toggle="dropdown"
            aria-expanded="false">
            <img src="../Assets/icons8-avatar-24.png" alt="User" class="me-2" />
            <?php echo htmlspecialchars($_SESSION['username']); ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li><a class="dropdown-item text-danger" href="../PHP/logout.php">Đăng xuất</a></li>
          </ul>
        </div>

      </div>
    </div>
  </nav>

  <!-- Profile Card -->
  <div class="container mt-4">
    <div class="card">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <img src="../Assets/icons8-avatar-24.png" alt="Ảnh đại diện" class="img-fluid rounded-circle me-4" />
          <div>
            <h3 class="card-title">
              <?php echo htmlspecialchars($_SESSION['fullname'], ENT_QUOTES, 'UTF-8'); ?>
            </h3>
            <p class="card-text">
              <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
          </div>
        </div>
        <a class="btn btn-info" href="../layouts/personal.php">Sửa thông tin</a>

      </div>
    </div>
    <hr />

    <!-- Sidebar -->
    <div class="sidebar">
      <a href="#" class="active" data-section="posts">Bài viết</a>
      <a href="#" data-section="questions">Câu hỏi</a>
      <a href="#" data-section="answers">Câu trả lời</a>
      <a href="#" data-section="bookmarks">Bookmark</a>
      <a href="#" data-section="following">Đang theo dõi</a>
      <a href="#" data-section="followers">Người theo dõi</a>
    </div>
    <hr />
    <!-- Các section -->
    <div id="posts" class="section"><!-- Content -->
      <main class="container mt-4">
        <?php if (empty($posts)): ?>
        <p>Không có bài viết nào.</p>
        <?php endif; ?>
        <?php foreach ($posts as $post): ?>
        <div class="post-container border rounded mb-3 p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div class="post-content">
              <p class="post-meta mb-1">
                <img src="../Assets/icons8-avatar-20.png" alt="Avatar" class="avatar" />
                <span id="author">
                  <?php echo htmlspecialchars($post['fullname']); ?>
                </span>
                <span id="date">
                  <?php echo date("h:i A, d/m/Y", strtotime($post['created_at'])); ?>
                </span>
              </p>

              <h5>
                <a class="title" href="view_post.php?id=<?php echo $post['posts_id']; ?>">
                  <?php echo htmlspecialchars($post['title']); ?>
                </a>
              </h5>
            </div>

            <!-- Nút xóa bên phải -->
            <div>
              <a href="../PHP/delete_post.php?id=<?php echo $post['posts_id']; ?>" class="btn btn-sm btn-danger"
                onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                <img src="../Assets/icons8-delete-20.png" alt="Delete" />
              </a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>


        <!-- Phân trang -->
        <?php
          // Gọi mã phân trang
          $total_posts_query = $conn->query("SELECT COUNT(*) AS total FROM baiviet");
          $total_posts = $total_posts_query->fetch_assoc()['total'];
          $total_pages = ceil($total_posts / $limit);

          echo '<nav class="mt-4">';
          echo '<ul class="pagination justify-content-center">';
          if ($page > 1) {
              echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">&laquo; Trang trước</a></li>';
          }
          for ($i = 1; $i <= $total_pages; $i++) {
              $active = ($i == $page) ? 'active' : '';
              echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
          }
          if ($page < $total_pages) {
              echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Trang sau &raquo;</a></li>';
          }
          echo '</ul>';
          echo '</nav>';
          ?>
      </main>
    </div>
    <div id="questions" class="section" style="display: none;">Không có gì ở đây</div>
    <div id="answers" class="section" style="display: none;">Không có gì ở đây</div>
    <div id="bookmarks" class="section" style="display: none;">Không có gì ở đây</div>
    <div id="following" class="section" style="display: none;">Không có gì ở đây</div>
    <div id="followers" class="section" style="display: none;">Không có gì ở đây</div>
    <hr>

  </div>

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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Lấy tất cả các nút sidebar và các section
      const sidebarLinks = document.querySelectorAll(".sidebar a");
      const sections = document.querySelectorAll(".section");

      // Lặp qua từng nút
      sidebarLinks.forEach(link => {
        link.addEventListener("click", function (event) {
          event.preventDefault(); // Ngăn tải lại trang

          // Xóa class 'active' khỏi tất cả các nút
          sidebarLinks.forEach(link => link.classList.remove("active"));

          // Thêm class 'active' vào nút được nhấn
          this.classList.add("active");

          // Ẩn tất cả các section
          sections.forEach(section => {
            section.style.display = "none";
          });

          // Hiển thị section tương ứng với nút được nhấn
          const targetSection = document.getElementById(this.dataset.section);
          if (targetSection) {
            targetSection.style.display = "block";
          }
        });
      });
    });

  </script>
  <!-- Thông báo xóa thành công -->
  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');

    if (message === 'deleted') {
      Swal.fire({
        icon: 'success',
        title: 'Xóa thành công',
        text: 'Bài viết đã được xóa!',
        timer: 3000,
        showConfirmButton: false
      });
    }

    // Xóa tham số message khỏi URL
    urlParams.delete('message');
    window.history.replaceState({}, document.title, window.location.pathname);
  </script>
</body>

</html>