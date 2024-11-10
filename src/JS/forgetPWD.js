const emailInput = document.getElementById("email");
const submitBtn = document.getElementById("submitBtn");

emailInput.addEventListener("input", function () {
  if (emailInput.value.trim() !== "") {
    submitBtn.classList.add("enabled");
    submitBtn.disabled = false;
  } else {
    submitBtn.classList.remove("enabled");
    submitBtn.disabled = true;
  }
});
