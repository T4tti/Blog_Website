<?php
// admin.php
session_start();

require_once '../PHP/config.php';


// Kiểm tra xem người dùng có phải admin không
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: ../layouts/login.html?access-error=access_denied");
    exit();
}


// Xóa bài viết
if (isset($_POST['delete_post'])) {
    $post_id = $_POST['post_id'];
    $conn->query("DELETE FROM baiviet WHERE posts_id = $post_id");
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
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Thêm Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../CSS/admin.css">

</head>

<body>
  <!-- Navbar -->
  <?php include '../layouts/navbar.php'; ?>


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
              <button type="button" class="btn btn-danger btn-sm"
                onclick="confirmDeletePost(<?php echo $row['posts_id']; ?>)">Xóa</button>
              <form id="delete-post-<?php echo $row['posts_id']; ?>" method="POST" style="display: none;">
                <input type="hidden" name="post_id" value="<?php echo $row['posts_id']; ?>">
                <input type="hidden" name="delete_post" value="1">
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
              <button type="button" class="btn btn-danger btn-sm"
                onclick="confirmDeleteUser(<?php echo $row['id']; ?>)">Xóa</button>
              <form id="delete-user-<?php echo $row['id']; ?>" method="POST" style="display: none;">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="delete_user" value="1">
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
  <?php include '../layouts/footer.php'; ?>

  <!-- Thêm Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function confirmDeletePost(postId) {
      Swal.fire({
        title: 'Bạn có chắc chắn?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(`delete-post-${postId}`).submit();
        }
      });
    }

    function confirmDeleteUser(userId) {
      Swal.fire({
        title: 'Bạn có chắc chắn?',
        text: "Tài khoản này sẽ bị xóa vĩnh viễn!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(`delete-user-${userId}`).submit();
        }
      });
    }
  </script>
</body>

</html>

<?php $conn->close(); ?>