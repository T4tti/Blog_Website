//Kiểm tra dữ liệu nhập vào

//lấy element từ form
const frmLogin = document.getElementById("frmLogin");
const username = document.getElementById("username");
const password = document.getElementById("pwd");

//lấy element thông báo lỗi
const usernameError = document.getElementById("error-username");
const passwordError = document.getElementById("error-pwd");
const loginError = document.getElementById("error-login");

frmLogin.addEventListener("submit", function (e) {
  //ngăn chặn sự kiện mặc định của form
  e.preventDefault();

  //kiểm tra dữ liệu nhập vào
  if (!username.value) {
    usernameError.style.display = "block";
  } else {
    usernameError.style.display = "none";
  }
  if (!password.value) {
    passwordError.style.display = "block";
  } else {
    passwordError.style.display = "none";
  }
});
