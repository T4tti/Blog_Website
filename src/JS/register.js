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

const termsCheckbox = document.getElementById("terms");
const registerBtn = document.getElementById("registerBtn");

function validateForm() {
  let allFilled = fields.every(
    (field) => document.getElementById(field.id).value.trim() !== ""
  );
  return allFilled && termsCheckbox.checked;
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
