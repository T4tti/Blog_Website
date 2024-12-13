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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../CSS/index.css" />
</head>

<body>
  <!-- Navbar -->
  <?php include '../layouts/navbar.php'; ?>

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
  <?php include '../layouts/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>        
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Check form search bar -->
  <script>
    const form = document.getElementById('frm-search');
    const input =document.getElementById('query');

    form.addEventListener('submit', (e) => {
      if (input.value.trim() === '') {
        e.preventDefault();
        Swal.fire({
          icon: 'alert',
          title: 'Lỗi',
          text: 'Vui lòng nhập từ khóa tìm kiếm!',
          timer: 1000,
          showConfirmButton: false
        });
      }
    });
  </script>

  <!-- Check alert logout -->
  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const logout = urlParams.get('logout');

    if (logout === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Đăng xuất thành công!',
        text: 'Hẹn gặp lại bạn!',
        timer: 2000,
        showConfirmButton: false
      });
    }

    // Xóa tham số logout khỏi URL
    urlParams.delete('logout');
    window.history.replaceState({}, document.title, window.location.pathname);

  </script>

</body>

</html>