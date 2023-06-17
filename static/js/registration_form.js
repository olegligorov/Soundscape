const form = document.getElementById("registration_form");
const errorMessage = document.getElementById("error");
const successText = document.getElementById("success");

console.log("TUKA!");

form.addEventListener("submit", function (event) {
  event.preventDefault();

  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const confirm_password = document.getElementById("confirm_password").value;

  // reset the error message
  errorMessage.textContent = "";
  errorMessage.style.display = "none";

  let isValid = true;

  if (name.length > 100) {
    errorMessage.textContent += "Invalid name, the name should be less than 50 characters characters long. \n";
    isValid = false;
  }
  if (!emailIsValid(email)) {
    errorMessage.textContent += "The email should be a valid email address. \n";
    isValid = false;
  }
  if (!passwordIsValid(password)) {
    // errorMessage.textContent += "Invalid password, the password should be at least 8 characters and should contain at least one uppercase letter, one lowercase letter and one number. \n";
    errorMessage.textContent += "Invalid password, the password should be at least 8 characters. \n";
    isValid = false;
  }

  if (confirm_password != password) {
    errorMessage.textContent += "The password confirmation should match the actual password. \n";
    isValid = false;
  }

  if (!isValid) {
    errorMessage.style.display = "block";
    return;
  }

  form.submit();
});

function emailIsValid(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function passwordIsValid(password) {
//   return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/.test(password);
    return /^.{8,}$/.test(password);
}
