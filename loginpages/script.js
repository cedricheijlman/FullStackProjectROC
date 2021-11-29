const eye = document.querySelector(".eye");

eye.addEventListener("click", function () {
  let check = document.getElementById("password");
  if (check.type === "password") {
    check.type = "text";
    eye.classList.remove("fa-eye");
    eye.classList.add("fa-eye-slash");
  } else {
    check.type = "password";
    eye.classList.add("fa-eye");
    eye.classList.remove("fa-eye-slash");
  }
});
