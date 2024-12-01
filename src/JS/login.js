// Lấy element từ form
const frmLogin = document.getElementById("frmLogin");
const username = document.getElementById("username");
const password = document.getElementById("pwd");

// Lấy element thông báo lỗi
const usernameError = document.getElementById("error-username");
const passwordError = document.getElementById("error-pwd");
const loginError = document.getElementById("error-login");

// Xử lý sự kiện submit của form
frmLogin.addEventListener("submit", function (e) {
  // Ngăn chặn sự kiện mặc định của form
  e.preventDefault();

  // Cờ kiểm tra hợp lệ
  let isValid = true;

  // Kiểm tra tên người dùng
  if (!username.value.trim()) {
    usernameError.style.display = "block";
    isValid = false;
  } else {
    usernameError.style.display = "none";
  }

  // Kiểm tra mật khẩu
  if (!password.value.trim()) {
    passwordError.style.display = "block";
    isValid = false;
  } else {
    passwordError.style.display = "none";
  }

  // Nếu form không hợp lệ, dừng xử lý
  if (!isValid) return;
});

// Lấy phần tử input password và nút toggle
const passwordInput = document.getElementById("pwd");
const togglePasswordButton = document.querySelector(".toggle-password");
const eyeIcon = document.getElementById("eye-icon");

// Thêm sự kiện click cho nút toggle
togglePasswordButton.addEventListener("click", function () {
  // Kiểm tra trạng thái hiện tại và thay đổi type
  const isPasswordVisible = passwordInput.type === "text";
  passwordInput.type = isPasswordVisible ? "password" : "text";

  // Thay đổi biểu tượng SVG
  if (isPasswordVisible) {
    eyeIcon.innerHTML = `<path d="M12 4.5C7.5 4.5 3.73 7.11 2 12c1.73 4.89 5.5 7.5 10 7.5s8.27-2.61 10-7.5c-1.73-4.89-5.5-7.5-10-7.5zm0 12c-2.49 0-4.5-2.01-4.5-4.5s2.01-4.5 4.5-4.5 4.5 2.01 4.5 4.5-2.01 4.5-4.5 4.5zm0-7c-1.38 0-2.5 1.12-2.5 2.5s1.12 2.5 2.5 2.5 2.5-1.12 2.5-2.5-1.12-2.5-2.5-2.5z""/>`;
  } else {
    eyeIcon.innerHTML = `<path d="M12 4.5C7.5 4.5 3.73 7.11 2 12c.85 2.41 2.31 4.44 4.22 5.88L3 21l1.5 1.5 18-18L20.5 1.5 15.2 6.8C14.11 6.3 13.07 6 12 6c-2.49 0-4.5 2.01-4.5 4.5S9.51 15 12 15c.77 0 1.49-.2 2.13-.54L12 18c-3.31 0-6-2.69-6-6 0-3.31 2.69-6 6-6 .39 0 .76.05 1.13.13L9.5 7.5C9.19 7.19 9 7 9 7 7.67 7 6.5 8.17 6.5 9.5 6.5 10.28 6.86 11 7.5 11.5 7.65 11.88 7.5 12 7.5 12s0 0 0 0h1c0 .5-.25.75-.5.75H8L12 14.5c.5 0 1-.25 1.5-.75H13l1.5-1.5H12l1.5-1.5h2l-1.25 1.25 5.5-5.5c-.47-.36-.99-.66-1.53-.88z"/>`;
  }
});
