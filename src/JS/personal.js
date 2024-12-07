const notequal = "Mật khẩu xác nhận không khớp";
const specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;
const lengthWarning = "Mật khẩu phải chứa ít nhất 8 ký tự.";
const strengthWarning = "Mật khẩu phải chứa ít nhất một ký tự đặc biệt";

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("frm-pwd");
  const passwordCur = document.getElementById("current_password");
  const passwordNew = document.getElementById("new_password");
  const passwordConf = document.getElementById("confirm_password");

  form.addEventListener("submit", (e) => {
    let isValid = true;

    // Lấy các giá trị input
    const currentPassword = document
      .getElementById("current_password")
      .value.trim();
    const newPassword = document.getElementById("new_password").value.trim();
    const confirmPassword = document
      .getElementById("confirm_password")
      .value.trim();

    // Các phần tử thông báo lỗi
    const errorCur = document.getElementById("error-cur");
    const errorNew = document.getElementById("error-new");
    const errorConf = document.getElementById("error-conf");

    // Reset trạng thái lỗi
    errorCur.style.display = "none";
    errorNew.style.display = "none";
    errorConf.style.display = "none";

    // Kiểm tra mật khẩu hiện tại
    if (!currentPassword) {
      errorCur.style.display = "block";
      passwordCur.parentElement.classList.add("invalid");
      isValid = false;
    }

    // Kiểm tra mật khẩu mới
    if (!newPassword) {
      errorNew.style.display = "block";
      passwordNew.parentElement.classList.add("invalid");
      isValid = false;
    } else if (newPassword.length < 8) {
      errorNew.textContent = lengthWarning;
      errorNew.style.display = "block";
      passwordNew.parentElement.classList.add("invalid");
      isValid = false;
    } else if (!specialCharRegex.test(newPassword)) {
      errorNew.textContent = strengthWarning;
      errorNew.style.display = "block";
      passwordNew.parentElement.classList.add("invalid");
      isValid = false;
    }

    // Kiểm tra xác nhận mật khẩu
    if (!confirmPassword) {
      errorConf.style.display = "block";
      passwordConf.parentElement.classList.add("invalid");
      isValid = false;
    } else if (newPassword !== confirmPassword) {
      errorConf.textContent = notequal;
      errorConf.style.display = "block";
      passwordConf.parentElement.classList.add("invalid");
      isValid = false;
    }

    // Ngăn không gửi form nếu có lỗi
    if (!isValid) {
      e.preventDefault();
    }
  });
});
