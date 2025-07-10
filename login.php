<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="/img/logo2.png">
  <title>Login - ClassConnect</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #5C6BC0;
      --secondary: #FFB74D;
      --accent: #4DB6AC;
      --background: #F9F9F9;
      --dark: #2E2E2E;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background-color: var(--background);
      margin: 0;
    }

    .navbar {
      background-color: #fff;
      border-bottom: 1px solid #eaeaea;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.6rem;
      color: var(--primary);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .navbar-brand i {
      font-size: 1.3rem;
      color: var(--secondary);
    }

    .nav-link {
      color: var(--dark);
      font-weight: 500;
      margin: 0 8px;
      position: relative;
    }

    .nav-link:hover {
      color: var(--accent);
    }

    .btn-primary {
      background-color: var(--secondary);
      border: none;
      border-radius: 25px;
      padding: 8px 20px;
      font-weight: 600;
    }

    .login-container {
      display: flex;
      min-height: 100vh;
    }

    .login-form {
      flex: 1;
      padding: 60px 40px;
      background-color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .login-form h2 {
      font-weight: 700;
      margin-bottom: 10px;
      color: var(--primary);
    }

    .login-form p {
      margin-bottom: 30px;
      color: #666;
    }

    .login-form label {
      font-weight: 500;
      margin-top: 15px;
    }

    .login-form input {
      margin-top: 5px;
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ddd;
      width: 100%;
    }

    .login-form button {
      margin-top: 30px;
      background-color: var(--primary);
      color: white;
      border: none;
      padding: 12px;
      border-radius: 30px;
      font-weight: 600;
    }

    .login-form button:hover {
      background-color: var(--accent);
    }

    .login-form .forgot {
      display: block;
      margin-top: 10px;
      font-size: 0.9rem;
      color: var(--accent);
      text-decoration: none;
    }

    .info-panel {
      flex: 1.2;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .info-panel h1 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .info-panel p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .info-feature {
      margin-top: 20px;
      font-size: 1rem;
      display: flex;
      align-items: center;
    }

    .info-feature i {
      margin-right: 10px;
      color: var(--secondary);
    }

    footer {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
      padding: 40px 0 20px;
    }

    footer h5 {
      font-weight: 700;
      margin-bottom: 20px;
    }

    footer a {
      color: #e0e0e0;
      text-decoration: none;
      font-size: 0.95rem;
    }

    footer a:hover {
      color: var(--secondary);
    }

    .footer-icon {
      font-size: 1.2rem;
      margin-right: 8px;
    }


    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }

      .info-panel {
        display: none;
      }
    }
  </style>
</head>

<body>

  <?php
  session_start();
  if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "guru") {
      header("Location: hal_guru/dashboard_guru/dashboard.php");
    } else {
      header("Location: hal_siswa/Dashboard/Dashboard.php");
    }
  }
  ?>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap"></i> ClassConnect</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="#footer">About</a></li>
        </ul>
        <a href="login.php" class="btn btn-primary ms-3">Login</a>
      </div>
    </div>
  </nav>

  <!-- Login Section -->
  <div class="login-container">
    <div class="login-form">
      <h2>Masuk ke ClassConnect</h2>
      <form method="POST" action="login/login.php">
        <label for="email">Email</label>
        <input type="text" id="email" name="email" required placeholder="Masukkan email Anda" />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Masukkan password" />

        <a class="forgot" href="#">Lupa password?</a>

        <button type="submit" name="login">Masuk</button>
        <div class="mt-3">
          Belum punya akun? <a href="login/signup.php" style="color: var(--accent); font-weight: 500;">Daftar</a>
        </div>
      </form>
    </div>
    <div class="info-panel">
      <h1>Selamat Datang di ClassConnect</h1>
      <p>Platform pembelajaran digital untuk guru dan siswa yang terintegrasi, nyaman, dan mudah digunakan.</p>
      <div class="info-feature"><i class="fas fa-book"></i> Akses materi 24/7</div>
      <div class="info-feature"><i class="fas fa-clock"></i> Pengingat otomatis</div>
      <div class="info-feature"><i class="fas fa-chalkboard-teacher"></i> Manajemen kelas cerdas</div>
    </div>
  </div>


</body>

</html>