document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const username = document.getElementById("loginUsername").value;
  const password = document.getElementById("loginPassword").value;

  const storedUsers = JSON.parse(localStorage.getItem("users")) || [];
  const user = storedUsers.find(
    (u) => u.username === username && u.password === password
  );

  if (user) {
    alert("Login berhasil!");
    // Simulasi redirect ke dashboard
    window.location.href = "hal_guru/dashboard_guru/dashboard.html";
  } else {
    alert("Username atau password salah.");
  }
});
