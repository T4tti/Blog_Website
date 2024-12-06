<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VietTechBlog Accounts</title>
    <link rel="icon" type="image/png" href="../Assets/favicon-32x32.png" sizes="32x32" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../CSS/personal.css">
</head>
<body class="bg-light">
  <div class="container profile-container">
    <h2 class="text-center">Thông Tin Cá Nhân</h2>
    <p class="text-center text-muted">Quản lý thông tin tài khoản của bạn.</p>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="accountTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Thông Tin Cá Nhân</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">Đổi Mật Khẩu</button>
      </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="accountTabsContent">
      <!-- Thông Tin Cá Nhân -->
      <div class="tab-pane fade show active" id="info" role="tabpanel">
        <div class="text-center profile-picture">
          <img src="../Assets/icons8-avatar-24.png" alt="Profile Picture">
        </div>
        <form>
          <div class="mb-3">
            <label for="username" class="form-label">Tên tài khoản</label>
            <input type="text" id="username" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label for="display-name" class="form-label">Tên hiển thị <span class="text-danger">*</span></label>
            <input type="text" id="display-name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="birthdate" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
            <input type="date" id="birthdate" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="gender" class="form-label">Giới tính <span class="text-danger">*</span></label>
            <select id="gender" class="form-select" required>
              <option value="">Chọn</option>
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
          </div>
          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-danger">Hủy bỏ</button>
            <button type="submit" class="btn btn-success">Cập nhật</button>
          </div>
        </form>
      </div>

      <!-- Đổi Mật Khẩu -->
      <div class="tab-pane fade" id="password" role="tabpanel">
        <form>
          <div class="mb-3">
            <label for="current-password" class="form-label">Mật khẩu hiện tại</label>
            <input type="password" id="current-password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="new-password" class="form-label">Mật khẩu mới</label>
            <input type="password" id="new-password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="confirm-password" class="form-label">Xác nhận mật khẩu mới</label>
            <input type="password" id="confirm-password" class="form-control" required>
          </div>
          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-danger">Hủy bỏ</button>
            <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
