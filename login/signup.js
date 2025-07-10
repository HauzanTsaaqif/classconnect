document.getElementById("signupForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const name = document.getElementById("signupName").value;
  const username = document.getElementById("signupUsername").value;
  const email = document.getElementById("signupEmail").value;
  const password = document.getElementById("signupPassword").value;

  const newUser = { name, username, email, password };

  const users = JSON.parse(localStorage.getItem("users")) || [];

  const userExists = users.some((u) => u.username === username || u.email === email);

  if (userExists) {
    alert("Username atau email sudah terdaftar.");
  } else {
    users.push(newUser);
    localStorage.setItem("users", JSON.stringify(users));
    alert("Pendaftaran berhasil! Silakan login.");
    window.location.href = "login.html";
  }
});
