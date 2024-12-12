<?php
session_start();

require_once '../PHP/config.php';

// Kiểm tra nếu chưa đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ../layouts/login.html?access-error=access_denied");
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
  <link rel="stylesheet" href="../CSS/profile.css"/>
</head>

<body>
  <!-- Navbar -->
  <?php include '../layouts/navbar.php'; ?>

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
          $total_posts_query = $conn->query("SELECT COUNT(*) AS total FROM baiviet WHERE user_id = (SELECT id FROM taikhoan WHERE username = '{$_SESSION['username']}')");
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
  <?php include '../layouts/footer.php'; ?>

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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>