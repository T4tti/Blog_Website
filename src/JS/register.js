const fields = [
  { id: "name", message: "Tên là bắt buộc" },
  { id: "email", message: "Email là bắt buộc" },
  { id: "username", message: "Tên tài khoản là bắt buộc" },
  { id: "password", message: "Mật khẩu là bắt buộc" },
  { id: "confirmPassword", message: "Xác nhận mật khẩu là bắt buộc" },
];

fields.forEach((field) => {
  const input = document.getElementById(field.id);
  const warning = input.nextElementSibling;

  input.addEventListener("blur", () => {
    if (input.value.trim() === "") {
      warning.style.display = "block";
      input.parentElement.classList.add("invalid");
    } else {
      warning.style.display = "none";
      input.parentElement.classList.remove("invalid");
    }
  });

  input.addEventListener("focus", () => {
    warning.style.display = "none";
    input.parentElement.classList.remove("invalid");
  });
});

const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");
const passwordLengthWarning = document.getElementById("passwordLengthWarning");
const passwordSpecialCharWarning = document.getElementById(
  "passwordSpecialCharWarning"
);
const passwordMatchWarning = document.getElementById("passwordMatchWarning");

password.addEventListener("input", () => {
  const specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;

  if (password.value.length < 8) {
    passwordLengthWarning.style.display = "block";
    password.parentElement.classList.add("invalid");
  } else {
    passwordLengthWarning.style.display = "none";
    password.parentElement.classList.remove("invalid");
  }

  if (!specialCharRegex.test(password.value)) {
    passwordSpecialCharWarning.style.display = "block";
    password.parentElement.classList.add("invalid");
  } else {
    passwordSpecialCharWarning.style.display = "none";
    password.parentElement.classList.remove("invalid");
  }
});

confirmPassword.addEventListener("input", () => {
  if (confirmPassword.value !== password.value) {
    passwordMatchWarning.style.display = "block";
    confirmPassword.parentElement.classList.add("invalid");
  } else {
    passwordMatchWarning.style.display = "none";
    confirmPassword.parentElement.classList.remove("invalid");
  }
});

const termsCheckbox = document.getElementById("terms");
const registerBtn = document.getElementById("registerBtn");

function validateForm() {
  let allFilled = fields.every(
    (field) => document.getElementById(field.id).value.trim() !== ""
  );
  let passwordsMatch = password.value === confirmPassword.value;
  let passwordValid =
    password.value.length >= 8 &&
    /[a-z]/.test(password.value) && // Ít nhất một chữ cái viết thường
    /\d/.test(password.value) && // Ít nhất một chữ số
    /[!@#$%^&*(),.?":{}|<>]/.test(password.value); // Ít nhất một ký tự đặc biệt
  return allFilled && passwordsMatch && passwordValid && termsCheckbox.checked;
}

termsCheckbox.addEventListener("change", function () {
  registerBtn.disabled = !validateForm();
  registerBtn.classList.toggle("enabled", validateForm());
});

document.getElementById("registerForm").addEventListener("input", function () {
  registerBtn.disabled = !validateForm();
  registerBtn.classList.toggle("enabled", validateForm());
});

document
  .getElementById("registerForm")
  .addEventListener("submit", function (event) {
    fields.forEach((field) => {
      const input = document.getElementById(field.id);
      const warning = input.nextElementSibling;
      if (input.value.trim() === "") {
        warning.style.display = "block";
        input.parentElement.classList.add("invalid");
      }
    });
    if (!validateForm()) {
      event.preventDefault();
    }
  });
